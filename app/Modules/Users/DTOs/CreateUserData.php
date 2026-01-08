<?php

namespace App\Modules\Users\DTOs;

use App\Modules\Users\Http\Requests\StoreUserRequest;

final class CreateUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {}

    public static function fromRequest(StoreUserRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );
    }
}
