<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function getUser(User $user);
    public function deleteUser(User $user);
    public function createUser(Request $request);
    public function updateUser(User $user, Request $request);
}
