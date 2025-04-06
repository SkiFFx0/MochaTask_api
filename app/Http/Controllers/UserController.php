<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return ApiResponse::success('Users list', [
            'users' => $users
        ]);
    }

    public function show(User $user)
    {
        return ApiResponse::success('User', [
            'user' => $user
        ]);
    }
}
