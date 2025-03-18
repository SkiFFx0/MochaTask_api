<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registerUserData = $request->validated();

        $user = User::query()->create($registerUserData);

        return ApiResponse::success('User created successfully', [
            'user' => $user
        ]);
    }

    public function login(LoginRequest $request)
    {
        $loginUserData = $request->validated();

        $user = User::query()->where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password))
        {
            return ApiResponse::error('Invalid login credentials', null, 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Logged in successfully', [
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ApiResponse::success('Logged out successfully');
    }
}
