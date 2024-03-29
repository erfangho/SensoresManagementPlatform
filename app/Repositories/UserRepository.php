<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        if ($user['role_id'] === 1) {
            return response()->json([
                'message' => 'این کاربر را نمی توان حذف کرد.',
            ], ResponseAlias::HTTP_FORBIDDEN);
        } else {
            if ($user->deleteOrFail()) {
                return response()->json([
                    'message' => 'کاربر با موفقیت حذف شد.',
                ], ResponseAlias::HTTP_OK);
            } else {
                return $user->deleteOrFail();
            }
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

        // TODO should we hash password in front side?
        $userDetails['password'] = Hash::make($userDetails['password']);

        return [
            'user' => User::create($userDetails),
            'message' => 'کاربر با موفقیت ساخته شد',
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

        if (isset($newDetails['password'])) {
            $newDetails['password'] = Hash::make($newDetails['password']);
        }

        if ($user->update($newDetails)) {
            return [
                'user' => $user,
                'message' => 'کاربر با موفقیت به روز رسانی شد',
            ];
        } else {
            return $user->update($newDetails);
        }
    }
}
