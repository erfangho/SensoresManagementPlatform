<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUser($user)
    {
        return $user;
    }

    public function deleteUser($user)
    {
        if ($user->deleteOrFail()) {
            return [
                'message' => 'user deleted successfully',
            ];
        } else {
            return $user->deleteOrFail();
        }
    }

    public function createUser(Request $request)
    {
        $userDetails = $request->only([
            'role_id',
            'name',
            'email',
            'phone',
            'password',
        ]);

        $userDetails['role_id'] = (int)$userDetails['role_id'];

        return [
            'user' => User::create($userDetails),
            'message' => 'user created successfully',
        ];
    }

    public function updateUser($user, Request $request)
    {
        $newDetails = $request->only([
            'role_id',
            'name',
            'email',
            'phone',
            'password',
        ]);

        if (isset($newDetails['role_id'])) {
            $newDetails['role_id'] = (int)$newDetails['role_id'];
        }

        if ($user->update($newDetails)) {
            return [
                'user' => $user,
                'message' => 'user updated successfully',
            ];
        } else {
            return $user->update($newDetails);
        }
    }
}
