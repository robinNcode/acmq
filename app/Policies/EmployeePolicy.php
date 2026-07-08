<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

/**
 * Policy for Employee management.
 * Admins: full CRUD across all branches.
 * Branch users: CRUD only within their own branch.
 */
class EmployeePolicy
{
    public function before(User $authUser, string $ability): ?bool
    {
        if ($authUser->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $authUser): bool { return true; }

    public function view(User $authUser, Employee $employee): bool
    {
        return $authUser->branch_id === $employee->branch_id;
    }

    public function create(User $authUser): bool { return $authUser->branch_id !== null; }

    public function update(User $authUser, Employee $employee): bool
    {
        return $authUser->branch_id === $employee->branch_id;
    }

    public function delete(User $authUser, Employee $employee): bool
    {
        return $authUser->branch_id === $employee->branch_id;
    }
}
