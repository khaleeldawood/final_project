<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function createNotification(User $user, string $message, string $type, ?string $linkUrl): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'message' => $message,
            'type' => $type,
            'link_url' => $linkUrl,
            'is_read' => false,
        ]);
    }

    public function getUserNotifications(int $userId): array
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }

    public function getUnreadNotifications(int $userId): array
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }

    public function getNotificationsByType(int $userId, string $type): array
    {
        return Notification::where('user_id', $userId)
            ->where('type', $type)
            ->orderByDesc('created_at')
            ->get()
            ->values()
            ->all();
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->is_read = true;
        $notification->save();
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
