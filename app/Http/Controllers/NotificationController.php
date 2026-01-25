<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $isRead = $request->query('isRead');
        $type = $request->query('type');

        if ($type) {
            $notifications = $this->notificationService->getNotificationsByType($user->id, $type);
        } elseif ($isRead !== null && filter_var($isRead, FILTER_VALIDATE_BOOLEAN) === false) {
            $notifications = $this->notificationService->getUnreadNotifications($user->id);
        } else {
            $notifications = $this->notificationService->getUserNotifications($user->id);
        }

        return response()->json($notifications);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['unreadCount' => $this->notificationService->getUnreadCount($user->id)]);
    }

    public function markAsRead(int $id): JsonResponse
    {
        $this->notificationService->markAsRead($id);
        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $this->notificationService->markAllAsRead($user->id);
        return response()->json(['message' => 'All notifications marked as read']);
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
