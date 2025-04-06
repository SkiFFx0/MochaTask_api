<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\Member\AddRoleRequest;
use App\Http\Requests\Member\AddUserRequest;
use App\Models\CompanyUser;
use App\Models\User;

class MemberController extends Controller
{
    public function addUser(AddUserRequest $request)
    {
        $request->validated();

        $companyId = $request->company_id;
        $userId = $request->user_id;
        $role = CompanyRole::MEMBER;

        $membership = CompanyUser::query()
            ->withTrashed()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        if ($membership)
        {
            return ApiResponse::error('User already in the company');
        }

        // Assign user to a company
        CompanyUser::setCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('User added successfully');
    }

    public function addRole(AddRoleRequest $request, User $user)
    {
        $validated = $request->validated();

        $companyId = $validated['company_id'];
        $userId = $user->id;
        $role = CompanyRole::from($validated['role']); // Convert string to Enum

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User is not in the company');
        }

//        $roleExists = CompanyUser::withTrashed()
//            ->where('company_id', $companyId)
//            ->where('user_id', $userId)
//            ->where('role', $role)
//            ->first();

        CompanyUser::withTrashed()->updateOrCreate([ //TODO when refactor use this by MEREY
            'company_id' => $companyId,
            'user_id' => $userId,
            'role' => $role
        ], [
            'deleted_at' => null
        ]);

        return ApiResponse::success('Role added successfully');
    }

    public function removeRole(RemoveRoleRequest $request, User $user, $role)
    {
        $validated = $request->validated();

        $companyId = $validated['company_id'];
        $userId = $user->id;
        $role = CompanyRole::from($role); // Convert string to Enum

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User is not in the company');
        }

        $roleExists = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->exists();

        if (!$roleExists)
        {
            return ApiResponse::error('User doesn\'t have this role');
        }

        CompanyUser::unsetCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('Role removed successfully');
    }

    public function removeUser(RemoveUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $companyId = $validated['company_id'];
        $userId = $user->id;

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User already is not in the company');
        }

        CompanyUser::unsetCompanyUser($companyId, $userId);

        return ApiResponse::success('User deleted successfully');
    }
}
//TODO add error responses to: attempting deleting yourself, deleting owner
