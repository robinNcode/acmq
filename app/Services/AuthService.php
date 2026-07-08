<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function attemptLogin(string $email, string $password, bool $remember = false): bool
    {
        return Auth::attempt(
            ['email' => $email, 'password' => $password],
            $remember
        );
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
