<?php

namespace App\Modules\Auth\DTOs;

use App\Modules\Auth\Http\Requests\LoginRequest;

final class LoginData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        $data = $request->validated();

        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }
}
