<?php namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updatePassword(User $user, string $password): bool
    {
        $user->password = Hash::make($password);
        return $user->save();
    }
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getAllUsers(): array
    {
        return User::all()->toArray();
    }

}