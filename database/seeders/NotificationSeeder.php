<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@north.edu')->firstOrFail();
        $student1 = User::where('email', 'student1@north.edu')->firstOrFail();
        $student3 = User::where('email', 'student3@citytech.edu')->firstOrFail();

        Notification::create([
            'user_id' => $admin->id,
            'message' => 'Your event "River Cleanup Drive" has been approved.',
            'type' => 'EVENT_UPDATE',
            'is_read' => true,
            'link_url' => '/events',
        ]);

        Notification::create([
            'user_id' => $student1->id,
            'message' => 'You earned the Contributor badge.',
            'type' => 'BADGE_EARNED',
            'is_read' => false,
            'link_url' => '/badges',
        ]);

        Notification::create([
            'user_id' => $student3->id,
            'message' => 'Your blog post is pending approval.',
            'type' => 'BLOG_APPROVAL',
            'is_read' => false,
            'link_url' => '/blogs',
        ]);
    }
}
