<?php

namespace App\Modules\Inventory\Policies;

use App\Models\User;

class InventoryPolicy
{
    private function hasRole(User $user, array $roles): bool
    {
        return in_array(($user->role ?? null), $roles, true);
    }

    public function view(User $user): bool
    {
        return $this->hasRole($user, ['admin', 'seller']);
    }

    public function manage(User $user): bool
    {
        // Stock changes should be staff only
        return $this->hasRole($user, ['admin', 'seller']);
    }
}
