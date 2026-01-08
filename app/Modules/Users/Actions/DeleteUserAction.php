<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Modules\Users\Repositories\Contracts\UserRepository;

class DeleteUserAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->users->delete($user);
        });
    }
}
