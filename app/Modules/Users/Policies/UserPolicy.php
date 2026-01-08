<?php

namespace App\Modules\Users\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Helper: checks if a user is admin.
     * Assumes you store role in users.role column.
     */
    private function isAdmin(User $authUser): bool
    {
        return ($authUser->role ?? null) === 'admin';
    }

    public function viewAny(User $authUser): bool
    {
        return $this->isAdmin($authUser);
    }

    public function view(User $authUser, User $target): bool
    {
        // Admin can view anyone
        return $this->isAdmin($authUser);
    }

    public function create(User $authUser): bool
    {
        return $this->isAdmin($authUser);
    }

    public function update(User $authUser, User $target): bool
    {
        // Admin can update anyone (including another admin if you allow)
        return $this->isAdmin($authUser);
    }

    public function delete(User $authUser, User $target): bool
    {
        // Don't allow admin to delete themselves by mistake
        if ($authUser->id === $target->id) {
            return false;
        }

        return $this->isAdmin($authUser);
    }
}
