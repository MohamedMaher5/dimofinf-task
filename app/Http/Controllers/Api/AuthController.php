<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $data['remember_token'] = Str::random(10);

        $user = User::create($data);

        return (new UserResource($user))->additional([
            'token' => $user->createToken('authToken')->accessToken
        ]);
    }


    /**
     * @param LoginRequest $request
     * @return UserResource|Response|Application|ResponseFactory
     */
    public function login(LoginRequest $request): UserResource|Response|Application|ResponseFactory
    {
        $login = $request->validated();

        if(!Auth::guard('web')->attempt($login)) {
            return response(['message' => 'Invalid login credentials.'], 401);
        }

        $user = Auth::guard('web')->user();

        return (new UserResource($user))->additional([
            'token' => $user->createToken('authToken')->accessToken
        ]);
    }

    /**
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function logout(Request $request): Response|Application|ResponseFactory
    {
        $token = $request->user()->token();
        $token->revoke();
        return response(['message' => 'You have been successfully logged out!'], 200);
    }
}
