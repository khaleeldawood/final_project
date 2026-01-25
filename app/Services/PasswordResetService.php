<?php

namespace App\Services;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetService
{
    private const TOKEN_EXPIRY_MINUTES = 15;

    public function __construct(private EmailService $emailService)
    {
    }

    public function createPasswordResetToken(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return;
        }

        PasswordResetToken::where('email', $user->email)->delete();

        $rawToken = Str::random(32);
        $tokenHash = Hash::make($rawToken);

        PasswordResetToken::create([
            'email' => $user->email,
            'user_id' => $user->id,
            'token_hash' => $tokenHash,
            'expiry_date' => Carbon::now()->addMinutes(self::TOKEN_EXPIRY_MINUTES),
            'used' => false,
            'created_at' => Carbon::now(),
        ]);

        $this->emailService->sendPasswordResetEmail($user->email, $user->name, $rawToken);
    }

    public function resetPassword(string $rawToken, string $newPassword): void
    {
        $token = $this->findTokenByRawToken($rawToken);

        if (!$token) {
            throw new \InvalidArgumentException('Invalid or expired reset token');
        }

        if (!$token->isValid()) {
            throw new \InvalidArgumentException('Token has expired or already been used');
        }

        $user = $token->user;
        $user->password = Hash::make($newPassword);
        $user->save();

        $token->used = true;
        $token->save();
    }

    public function validateToken(string $rawToken): bool
    {
        $token = $this->findTokenByRawToken($rawToken);
        return $token !== null && $token->isValid();
    }

    private function findTokenByRawToken(string $rawToken): ?PasswordResetToken
    {
        return PasswordResetToken::all()
            ->first(fn (PasswordResetToken $token) => $token->token_hash && Hash::check($rawToken, $token->token_hash));
    }
}
