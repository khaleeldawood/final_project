<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private BlogService $blogService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $universityId = $request->query('universityId');
        $category = $request->query('category');
        $status = $request->query('status');
        $isGlobal = $request->query('isGlobal');
        $isGlobal = $isGlobal !== null ? filter_var($isGlobal, FILTER_VALIDATE_BOOLEAN) : null;

        $blogs = $this->blogService->getAllBlogs(
            $universityId ? (int) $universityId : null,
            $category,
            $status,
            $isGlobal
        );

        return response()->json($blogs);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->blogService->getBlogById($id));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'isGlobal' => 'nullable|boolean',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $blog = $this->blogService->createBlog($data, $user);

        return response()->json($this->blogService->getBlogById($blog->id), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'isGlobal' => 'nullable|boolean',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $blog = $this->blogService->updateBlog($id, $data, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($this->blogService->getBlogById($blog->id));
    }

    public function approve(int $id): JsonResponse
    {
        $this->blogService->approveBlog($id);
        return response()->json(['message' => 'Blog approved successfully']);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'nullable|string',
        ]);

        $this->blogService->rejectBlog($id, $data['reason'] ?? 'Not specified');
        return response()->json(['message' => 'Blog rejected']);
    }

    public function myBlogs(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($this->blogService->getBlogsByAuthor($user->id));
    }

    public function pending(): JsonResponse
    {
        return response()->json($this->blogService->getPendingBlogs());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        
        try {
            $this->blogService->deleteBlog($id, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Blog deleted successfully']);
    }

    private function getSessionUser(Request $request): ?User
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return null;
        }

        return User::find($userId);
    }
}
