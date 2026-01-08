<?php

namespace App\Modules\Products\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    private function hasRole(User $user, array $roles): bool
    {
        return in_array(($user->role ?? null), $roles, true);
    }

    public function viewAny(User $user): bool
    {
        // Logged-in users can view products (seller/admin/customer if you want)
        return $this->hasRole($user, ['admin', 'seller', 'customer']);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        // Only staff
        return $this->hasRole($user, ['admin', 'seller']);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->hasRole($user, ['admin', 'seller']);
    }

    public function delete(User $user, Product $product): bool
    {
        // Deleting products is dangerous; keep it for admin only.
        return $this->hasRole($user, ['admin']);
    }
}
