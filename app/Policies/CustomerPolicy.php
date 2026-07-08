<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

/**
 * Policy for Customer management.
 * Admins: full CRUD across all branches.
 * Branch users: CRUD only within their own branch.
 */
class CustomerPolicy
{
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $authUser): bool { return true; }

    public function view(User $authUser, Customer $customer): bool
    {
        return $authUser->branch_id === $customer->branch_id;
    }

    public function create(User $authUser): bool { return $authUser->branch_id !== null; }

    public function update(User $authUser, Customer $customer): bool
    {
        return $authUser->branch_id === $customer->branch_id;
    }

    public function delete(User $authUser, Customer $customer): bool
    {
        return $authUser->branch_id === $customer->branch_id;
    }
}
