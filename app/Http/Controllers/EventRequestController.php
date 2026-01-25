<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EventRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventRequestController extends Controller
{
    public function __construct(private EventRequestService $eventRequestService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'eventId' => 'required|integer',
            'role' => 'required|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $eventRequest = $this->eventRequestService->createRequest((int) $data['eventId'], $user, $data['role']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($eventRequest, 201);
    }

    public function accept(Request $request, int $id): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->eventRequestService->acceptRequest($id);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Request accepted']);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'nullable|string',
        ]);

        try {
            $this->eventRequestService->rejectRequest($id, $data['reason'] ?? 'Not specified');
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Request rejected']);
    }

    public function myRequests(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($this->eventRequestService->getMyPendingRequests($user->id));
    }

    public function eventRequests(int $eventId): JsonResponse
    {
        return response()->json($this->eventRequestService->getPendingRequestsForEvent($eventId));
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
