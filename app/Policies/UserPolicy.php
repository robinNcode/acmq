<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy for User management.
 * Only admins can manage users.
 */
class UserPolicy
{
    /**
     * Admins bypass all policy checks.
     */
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $authUser): bool   { return false; }
    public function view(User $authUser, User $user): bool { return false; }
    public function create(User $authUser): bool     { return false; }
    public function update(User $authUser, User $user): bool { return false; }
    public function delete(User $authUser, User $user): bool { return false; }
}
