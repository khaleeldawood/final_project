<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogReportSeeder extends Seeder
{
    public function run(): void
    {
        $blog = Blog::where('title', 'My First Mentorship Session')->firstOrFail();
        $reporter = User::where('email', 'student1@north.edu')->firstOrFail();

        BlogReport::create([
            'blog_id' => $blog->id,
            'reported_by' => $reporter->id,
            'reason' => 'Contains personal contact information.',
            'status' => 'PENDING',
        ]);
    }
}
