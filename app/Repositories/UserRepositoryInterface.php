<?php namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function updatePassword(User $user, string $password): bool;

    public function delete(User $user): bool;
}