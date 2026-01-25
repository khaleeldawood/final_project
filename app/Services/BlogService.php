<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;

class BlogService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    public function createBlog(array $data, User $author): Blog
    {
        $blog = new Blog();
        $blog->title = $data['title'];
        $blog->content = $data['content'];
        $blog->category = $data['category'];
        $blog->is_global = (bool) ($data['isGlobal'] ?? false);
        $blog->status = 'PENDING';
        $blog->author_id = $author->id;

        if (!$blog->is_global && $author->university_id) {
            $blog->university_id = $author->university_id;
        }

        $blog->save();

        return $blog;
    }

    public function updateBlog(int $blogId, array $data, User $currentUser): Blog
    {
        $blog = Blog::findOrFail($blogId);

        if ($blog->author_id !== $currentUser->id) {
            throw new \RuntimeException('You do not have permission to edit this blog');
        }

        if (!in_array($blog->status, ['PENDING', 'APPROVED'], true)) {
            throw new \RuntimeException('Only pending or approved blogs can be edited');
        }

        $wasApproved = $blog->status === 'APPROVED';
        if ($wasApproved) {
            $blog->status = 'PENDING';
        }

        $blog->title = $data['title'];
        $blog->content = $data['content'];
        $blog->category = $data['category'];
        $blog->is_global = (bool) ($data['isGlobal'] ?? false);

        if (!$blog->is_global && $currentUser->university_id) {
            $blog->university_id = $currentUser->university_id;
        } elseif ($blog->is_global) {
            $blog->university_id = null;
        }

        $blog->save();

        if ($wasApproved) {
            Notification::create([
                'user_id' => $currentUser->id,
                'message' => "Your blog '{$blog->title}' has been updated and requires re-approval",
                'type' => 'BLOG_APPROVAL',
                'link_url' => "/blogs/{$blog->id}",
                'is_read' => false,
            ]);
        }

        return $blog;
    }

    public function getAllBlogs(?int $universityId, ?string $category, ?string $status, ?bool $isGlobal): array
    {
        $query = Blog::with(['author.university', 'university']);

        if ($universityId !== null && $status !== null) {
            if ($status === 'APPROVED') {
                $query->where(function ($sub) use ($universityId) {
                    $sub->where('university_id', $universityId)
                        ->orWhere('is_global', true);
                })->where('status', $status);
            } else {
                $query->where('university_id', $universityId)->where('status', $status);
            }
        } elseif ($universityId !== null) {
            $query->where('university_id', $universityId);
        } elseif ($category !== null && $status !== null) {
            $query->where('category', $category)->where('status', $status);
        } elseif ($category !== null) {
            $query->where('category', $category);
        } elseif ($status !== null) {
            $query->where('status', $status);
        } elseif ($isGlobal !== null && $isGlobal && $status !== null) {
            $query->where('is_global', true)->where('status', $status);
        }

        $blogs = $query->orderByDesc('created_at')->get();

        return $blogs->map(fn (Blog $blog) => $this->mapBlog($blog))->values()->all();
    }

    public function getBlogById(int $blogId): array
    {
        $blog = Blog::with(['author.university', 'university'])->findOrFail($blogId);
        return $this->mapBlog($blog);
    }

    public function approveBlog(int $blogId): void
    {
        $blog = Blog::with('author')->findOrFail($blogId);

        if ($blog->status === 'APPROVED') {
            throw new \RuntimeException('Blog is already approved');
        }

        $blog->status = 'APPROVED';
        $blog->save();

        $author = $blog->author;
        $points = ($author->role ?? '') === 'STUDENT' ? 30 : 50;

        $this->gamificationService->awardPoints(
            $author,
            $points,
            'BLOG',
            $blog->id,
            "Blog approved: {$blog->title}"
        );

        Notification::create([
            'user_id' => $author->id,
            'message' => "Your blog '{$blog->title}' has been approved!",
            'type' => 'BLOG_APPROVAL',
            'link_url' => "/blogs/{$blog->id}",
            'is_read' => false,
        ]);
    }

    public function rejectBlog(int $blogId, string $reason): void
    {
        $blog = Blog::with('author')->findOrFail($blogId);
        $blog->status = 'REJECTED';
        $blog->save();

        Notification::create([
            'user_id' => $blog->author_id,
            'message' => "Your blog '{$blog->title}' was rejected. Reason: {$reason}",
            'type' => 'BLOG_APPROVAL',
            'link_url' => "/blogs/{$blog->id}",
            'is_read' => false,
        ]);
    }

    public function getBlogsByAuthor(int $authorId): array
    {
        $blogs = Blog::with(['author.university', 'university'])
            ->where('author_id', $authorId)
            ->orderByDesc('created_at')
            ->get();

        return $blogs->map(fn (Blog $blog) => $this->mapBlog($blog))->values()->all();
    }

    public function getPendingBlogs(): array
    {
        $blogs = Blog::with(['author.university', 'university'])
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->get();

        return $blogs->map(fn (Blog $blog) => $this->mapBlog($blog))->values()->all();
    }

    public function deleteBlog(int $blogId, User $currentUser): void
    {
        $blog = Blog::findOrFail($blogId);

        $isAuthor = $blog->author_id === $currentUser->id;
        $isAdmin = ($currentUser->role ?? '') === 'ADMIN';

        if (!$isAuthor && !$isAdmin) {
            throw new \RuntimeException('You do not have permission to delete this blog');
        }

        if ($isAuthor && !$isAdmin && $blog->status === 'APPROVED') {
            throw new \RuntimeException('Cannot delete an approved blog. Contact an admin.');
        }

        $blog->delete();
    }

    private function mapBlog(Blog $blog): array
    {
        $reportCount = BlogReport::where('blog_id', $blog->id)->count();

        return [
            'blogId' => $blog->id,
            'title' => $blog->title,
            'content' => $blog->content,
            'category' => $blog->category,
            'status' => $blog->status,
            'isGlobal' => (bool) $blog->is_global,
            'createdAt' => $blog->created_at ? Carbon::parse($blog->created_at)->toIso8601String() : null,
            'lastModifiedAt' => $blog->updated_at ? Carbon::parse($blog->updated_at)->toIso8601String() : null,
            'reportCount' => $reportCount,
            'author' => $blog->author ? [
                'userId' => $blog->author->id,
                'name' => $blog->author->name,
                'email' => $blog->author->email,
                'university' => $blog->author->university ? [
                    'universityId' => $blog->author->university->id,
                    'name' => $blog->author->university->name,
                ] : null,
            ] : null,
        ];
    }
}
