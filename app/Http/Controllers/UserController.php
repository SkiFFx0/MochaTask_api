<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use App\Models\CompanyUser;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return ApiResponse::success('Users list', [
            'users' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user)
        {
            return ApiResponse::error('User not found', null, 404);
        }

        return ApiResponse::success('User', [
            'user' => $user
        ]);
    }
}
