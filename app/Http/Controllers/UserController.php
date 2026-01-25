<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function getCurrentUser(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($this->userService->getCurrentUser($user->id));
    }

    public function getUserById(int $id): JsonResponse
    {
        return response()->json($this->userService->getUserById($id));
    }

    public function getAllUsers(): JsonResponse
    {
        return response()->json($this->userService->getAllUsers());
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'name' => 'nullable|string|min:2|max:255',
            'email' => 'nullable|email',
        ]);

        try {
            $updated = $this->userService->updateProfile($user, $data['name'] ?? null, $data['email'] ?? null);
            return response()->json($updated);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    public function changePassword(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string',
        ]);

        try {
            $this->userService->changePassword($user, $data['oldPassword'], $data['newPassword']);
            return response()->json(['message' => 'Password changed successfully']);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    public function setPassword(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'newPassword' => 'required|string',
        ]);

        $this->userService->setPassword($user, $data['newPassword']);

        return response()->json(['message' => 'Password set successfully']);
    }

    public function updateUniversity(Request $request): JsonResponse
    {
        $user = $this->getSessionUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'universityId' => 'nullable|integer',
        ]);

        try {
            $updated = $this->userService->updateUniversity($user, $data['universityId'] ?? null);
            return response()->json($updated);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
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