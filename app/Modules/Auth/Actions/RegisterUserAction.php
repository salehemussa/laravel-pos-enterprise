<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Modules\Auth\DTOs\RegisterData;
use App\Modules\Auth\Repositories\Contracts\UserRepository;
use App\Modules\Auth\Events\UserRegistered;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepository $users
    ) {}

    /**
     * Register a new user safely inside a DB transaction.
     * Why transaction? If later you add profile creation, role assign, etc
     * everything stays consistent.
     */
    public function execute(RegisterData $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->users->create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                // If you store role in users table:
                'role' => $data->role ?? 'customer',
            ]);

            // Fire a domain event (decouples side effects like email/sms)
            event(new UserRegistered($user));

            return $user;
        });
    }
}
