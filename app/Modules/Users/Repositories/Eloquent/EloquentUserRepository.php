<?php

namespace App\Modules\Users\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Users\Repositories\Contracts\UserRepository;

class EloquentUserRepository implements UserRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        // Keep querying logic centralized (easy to add filters later)
        return User::query()
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes);
        $user->save();

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
