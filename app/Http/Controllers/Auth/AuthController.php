<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private PasswordResetService $passwordResetService
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'universityId' => 'nullable|integer',
        ]);

        try {
            $response = $this->authService->register($data);
            $request->session()->put('user_id', $response['userId']);
            return response()->json($response);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response = $this->authService->login($data['email'], $data['password']);
            $request->session()->put('user_id', $response['userId']);
            return response()->json($response);
        } catch (\RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->forget('user_id');
        $request->session()->invalidate();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $this->passwordResetService->createPasswordResetToken($data['email']);

        return response()->json([
            'message' => 'If your email is registered, you will receive a password reset link shortly.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ]);

        if (strlen($data['newPassword']) < 8) {
            return response()->json(['error' => 'Password must be at least 8 characters'], 400);
        }

        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/', $data['newPassword']) !== 1) {
            return response()->json(['error' => 'Password must contain uppercase, lowercase, digit, and special character'], 400);
        }

        try {
            $this->passwordResetService->resetPassword($data['token'], $data['newPassword']);
            return response()->json([
                'message' => 'Password has been reset successfully. You can now log in with your new password.',
            ]);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

    public function validateResetToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required|string',
        ]);

        $isValid = $this->passwordResetService->validateToken($data['token']);
        return response()->json(['valid' => $isValid]);
    }

    public function session(Request $request): JsonResponse
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return response()->json([], 401);
        }

        $response = $this->authService->getCurrentUser($userId);
        return response()->json($response);
    }





    public function check(Request $request): JsonResponse
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return response()->json([], 401);
        }

        return response()->json(['ok' => true]);
    }
}
