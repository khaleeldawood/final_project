<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendVerificationEmail(string $email, string $name, string $token): void
    {
        Log::info('Verification email token generated', [
            'email' => $email,
            'name' => $name,
            'token' => $token,
        ]);
    }

    public function sendPasswordResetEmail(string $email, string $name, string $token): void
    {
        Log::info('Password reset token generated', [
            'email' => $email,
            'name' => $name,
            'token' => $token,
        ]);
    }
}
