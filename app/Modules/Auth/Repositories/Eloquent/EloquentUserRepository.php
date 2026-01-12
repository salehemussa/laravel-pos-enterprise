<?php

namespace App\Modules\Auth\Repositories\Eloquent;


use App\Models\User;
use App\Modules\Auth\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    public function create(array $attributes): User
    {
        // Central point for user persistence (easy to change later)
        return User::query()->create($attributes);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
