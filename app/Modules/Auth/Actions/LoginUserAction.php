<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;
use App\Modules\Auth\DTOs\LoginData;
use App\Modules\Auth\Repositories\Contracts\UserRepository;
use Illuminate\Auth\AuthenticationException;

class LoginUserAction
{
    public function __construct(
        private readonly UserRepository $users
    ) {}

    /**
     * Authenticate using JWT guard.
     * Returns token string + authenticated user.
     */
    public function execute(LoginData $data): array
    {
        // JWT attempt expects credentials
        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
        ];

        // `auth('api')` uses jwt driver because we set guard in config/auth.php
        $token = auth('api')->attempt($credentials);

        if (!$token) {
            throw new AuthenticationException('Invalid email or password.');
        }

        /** @var User $user */
        $user = auth('api')->user();

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
}
