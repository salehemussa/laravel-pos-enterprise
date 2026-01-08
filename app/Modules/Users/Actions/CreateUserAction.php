<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\Users\DTOs\CreateUserData;
use App\Modules\Users\Repositories\Contracts\UserRepository;

class CreateUserAction
{
    public function __construct(private readonly UserRepository $users) {}

    /**
     * Creates a user inside a transaction.
     * If later you add profile, branch assignment, audit logs, etc,
     * the whole operation remains consistent.
     */
    public function execute(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data) {
            return $this->users->create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'role' => $data->role,
            ]);
        });
    }
}
