<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EventParticipationRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventParticipationRequestController extends Controller
{
    public function __construct(private EventParticipationRequestService $requestService)
    {
    }

    public function submitRequest(Request $request, int $eventId): JsonResponse
    {
        $data = $request->validate([
            'requestedRole' => 'required|string',
        ]);

        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $response = $this->requestService->submitRequest($eventId, $user, $data['requestedRole']);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json($response);
    }

    public function getEventRequests(int $eventId): JsonResponse
    {
        return response()->json($this->requestService->getEventRequests($eventId));
    }

    public function getMyRequests(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($this->requestService->getUserRequests($user));
    }

    public function approveRequest(Request $request, int $requestId): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->requestService->approveRequest($requestId, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(null);
    }

    public function rejectRequest(Request $request, int $requestId): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->requestService->rejectRequest($requestId, $user);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return response()->json(null);
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
