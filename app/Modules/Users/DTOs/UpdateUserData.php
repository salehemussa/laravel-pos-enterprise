<?php

namespace App\Modules\Users\DTOs;

use App\Modules\Users\Http\Requests\UpdateUserRequest;

final class UpdateUserData
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $role = null,
    ) {}

    public static function fromRequest(UpdateUserRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            role: $data['role'] ?? null,
        );
    }
}
