<?php

namespace App\Services;

use App\Models\University;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getCurrentUser(int $userId): array
    {
        $user = User::with(['university', 'currentBadge'])->findOrFail($userId);
        return $this->mapUser($user);
    }

    public function getUserById(int $userId): array
    {
        $user = User::with(['university', 'currentBadge'])->findOrFail($userId);
        return $this->mapUser($user);
    }

    public function getAllUsers(): array
    {
        $users = User::with(['university', 'currentBadge'])->orderBy('name')->get();
        return $users->map(fn (User $user) => $this->mapUser($user))->values()->all();
    }

    public function updateProfile(User $user, ?string $name, ?string $email): array
    {
        if ($name !== null && trim($name) !== '') {
            $user->name = $name;
        }

        if ($email !== null && trim($email) !== '' && $email !== $user->email) {
            $exists = User::where('email', $email)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($exists) {
                throw new \InvalidArgumentException('Email already in use');
            }
            $user->email = $email;
        }

        $user->save();

        $user->load(['university', 'currentBadge']);
        return $this->mapUser($user);
    }

    public function changePassword(User $user, string $oldPassword, string $newPassword): void
    {
        if (!Hash::check($oldPassword, $user->password ?? '')) {
            throw new \InvalidArgumentException('Current password is incorrect');
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function setPassword(User $user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function updateUniversity(User $user, ?int $universityId): array
    {
        if ($universityId === null) {
            $user->university_id = null;
        } else {
            $university = University::find($universityId);
            if (!$university) {
                throw new \InvalidArgumentException('University not found');
            }
            $user->university_id = $university->id;
        }

        $user->save();
        $user->load(['university', 'currentBadge']);

        return $this->mapUser($user);
    }

    private function mapUser(User $user): array
    {
        return [
            'userId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'points' => $user->points ?? 0,
            'emailVerified' => (bool) ($user->email_verified ?? false),
            'university' => $user->university ? [
                'universityId' => $user->university->id,
                'name' => $user->university->name,
            ] : null,
            'currentBadge' => $user->currentBadge ? [
                'badgeId' => $user->currentBadge->id,
                'name' => $user->currentBadge->name,
                'description' => $user->currentBadge->description,
                'pointsThreshold' => $user->currentBadge->points_threshold,
            ] : null,
        ];
    }
}
