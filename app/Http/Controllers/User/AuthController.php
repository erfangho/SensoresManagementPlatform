<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $credentials = $request->only(['phone', 'password']);

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('phone', $request['phone'])->first();

        $token = $user->createToken("API KEY");

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accessToken);

        if (isset($token) and $token->delete()) {
            return response()->json(['message' => 'user logged out successfully']);
        }

        return response()->json(['message' => 'something went wrong'], ResponseAlias::HTTP_NOT_FOUND);
    }
}
