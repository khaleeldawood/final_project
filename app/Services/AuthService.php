<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\EmailVerificationToken;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(private EmailService $emailService)
    {
    }

    public function register(array $data): array
    {
        if (User::where('email', $data['email'])->exists()) {
            throw new \InvalidArgumentException('Email already registered');
        }

        $passwordErrors = PasswordValidator::validate($data['password'] ?? null);
        if (!empty($passwordErrors)) {
            throw new \InvalidArgumentException('Password validation failed: ' . implode(', ', $passwordErrors));
        }

        $university = null;
        if (!empty($data['universityId'])) {
            $university = University::find($data['universityId']);
            if (!$university) {
                throw new \InvalidArgumentException('University not found');
            }

            if (!empty($university->email_domain)) {
                $emailDomain = strtolower(substr($data['email'], strpos($data['email'], '@') + 1));
                if ($emailDomain !== strtolower($university->email_domain)) {
                    throw new \InvalidArgumentException('Email must be from ' . $university->email_domain . ' domain');
                }
            }
        }

        $defaultBadge = Badge::where('points_threshold', '<=', 0)
            ->orderByDesc('points_threshold')
            ->first();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'STUDENT',
            'points' => 0,
            'email_verified' => true,
            'university_id' => $university?->id,
            'current_badge_id' => $defaultBadge?->id,
        ]);

        return $this->buildAuthResponse($user, null);
    }

    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \RuntimeException('Invalid email or password');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \RuntimeException('Invalid email or password');
        }

        $token = Str::random(64);
        return $this->buildAuthResponse($user, $token);
    }

    public function getCurrentUser(int $userId): array
    {
        $user = User::findOrFail($userId);
        return $this->buildAuthResponse($user, null);
    }





    public function buildAuthResponse(User $user, ?string $token): array
    {
        return [
            'token' => $token,
            'userId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'points' => $user->points,
            'hasPassword' => !empty($user->password),
            'universityId' => $user->university?->id,
            'universityName' => $user->university?->name,
            'currentBadgeName' => $user->currentBadge?->name,
        ];
    }
}
