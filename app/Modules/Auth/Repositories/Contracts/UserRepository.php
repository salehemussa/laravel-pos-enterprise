<?php

namespace App\Modules\Auth\Repositories\Contracts;

use App\Models\User;

interface UserRepository
{
    public function create(array $attributes): User;

    public function findByEmail(string $email): ?User;
}
