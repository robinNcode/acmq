<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

/**
 * Policy for Product management.
 */
class ProductPolicy
{
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $authUser): bool { return true; }

    public function view(User $authUser, Product $product): bool { return true; }

    public function create(User $authUser): bool { return false; } // Branch users cannot create products (assumption)

    public function update(User $authUser, Product $product): bool { return false; } // Branch users cannot edit products (assumption)

    public function delete(User $authUser, Product $product): bool { return false; } // Branch users cannot delete products (assumption)

    /**
     * Determine whether the user can view the actual purchase price.
     */
    public function viewPurchasePrice(User $authUser, Product $product): bool
    {
        return $authUser->isAdmin();
    }
}
