<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\Users\DTOs\UpdateUserData;
use App\Modules\Users\Repositories\Contracts\UserRepository;

class UpdateUserAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user, UpdateUserData $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Build attributes dynamically (avoid overwriting with nulls)
            $attributes = array_filter([
                'name' => $data->name,
                'email' => $data->email,
                'role' => $data->role,
            ], fn ($v) => $v !== null);

            // Only hash and set password if it was provided
            if ($data->password !== null && $data->password !== '') {
                $attributes['password'] = Hash::make($data->password);
            }

            return $this->users->update($user, $attributes);
        });
    }
}
