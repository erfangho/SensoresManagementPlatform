<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\StoreUserRequest;
use App\Http\Requests\UserRequests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;


    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $currentUser = auth()->user();
//        dd(config('constants.roles.admin'));
        if (!Gate::allows('is-admin')) {
            return response()->json([
                'message' => 'شما دسترسی به این قسمت ندارید',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }

        return response()->json([
            'users' => $this->userRepository->getAllUsers(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequests\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        if (Gate::allows('is-admin')) {
            return response()->json(
                $this->userRepository->createUser($request)
                , ResponseAlias::HTTP_CREATED);
        } else {
            return response()->json([
                'message' => 'شما دسترسی به این قسمت ندارید',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        if (Gate::allows('is-admin')) {
            return response()->json([
                'user' => $this->userRepository->getUser($user),
            ]);
        }  else {
            return response()->json([
                'message' => 'شما دسترسی به این قسمت ندارید',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Gate::allows('is-admin')) {
            return response()->json(
                $this->userRepository->updateUser($user, $request)
                , ResponseAlias::HTTP_OK);
        } else {
            if (auth()->user()['phone'] === $user['phone']) {
                $request->only('password');

                return response()->json(
                    $this->userRepository->updateUser($user, $request)
                    , ResponseAlias::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'شما دسترسی به این قسمت ندارید',
                ], ResponseAlias::HTTP_FORBIDDEN);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if (Gate::allows('is-admin')) {
            return response()->json(
                $this->userRepository->deleteUser($user)
                , ResponseAlias::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'شما دسترسی به این قسمت ندارید',
            ], ResponseAlias::HTTP_FORBIDDEN);
        }
    }

    public function getUserByToken()
    {
        $user = auth()->user();

        return response()->json($user, ResponseAlias::HTTP_OK);
    }
}
