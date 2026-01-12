<?php

namespace App\Modules\Auth\DTOs;

use App\Modules\Auth\Http\Requests\RegisterRequest;

final class RegisterData{
    
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $role = null,
    ) {}

    /**
     * Convert validated HTTP request into a DTO.
     * Keeps actions/services independent from HTTP layer.
     */
    public static function fromRequest(RegisterRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'] ?? null
        );
    }
}
