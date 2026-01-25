<?php

namespace App\Services;

class PasswordValidator
{
    public static function validate(?string $password): array
    {
        $errors = [];

        if (!$password || strlen($password) < 8) {
            $errors[] = 'At least 8 characters';
        }

        if ($password && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'At least one uppercase letter';
        }

        if ($password && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'At least one lowercase letter';
        }

        if ($password && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'At least one number';
        }

        if ($password && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'At least one special character (!@#$%^&*...)';
        }

        return $errors;
    }
}
