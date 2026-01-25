<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Event;
use App\Models\University;
use App\Models\User;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalEvents' => Event::count(),
            'totalBlogs' => Blog::count(),
            'totalUniversities' => University::all()->count(),
            'activeUsers' => User::where('points', '>', 0)->count(),
            'pendingApprovals' => [
                'events' => Event::where('status', 'PENDING')->count(),
                'blogs' => Blog::where('status', 'PENDING')->count(),
            ],
            'usersByRole' => [
                'ADMIN' => User::where('role', 'ADMIN')->count(),
                'SUPERVISOR' => User::where('role', 'SUPERVISOR')->count(),
                'STUDENT' => User::where('role', 'STUDENT')->count(),
            ],
        ];

        return response()->json($data);
    }
}
