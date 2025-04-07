<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\Member\AddRoleRequest;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyMemberController extends Controller
{
    public function addRole(AddRoleRequest $request, User $user)
    {
        $validated = $request->validated();

        $userId = $user->id;
        $companyId = $request->company_id;

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User is not in the company');
        }

        $role = CompanyRole::from($validated['role']); // Convert string to Enum

        if ($role === CompanyRole::OWNER)
        {
            return ApiResponse::error('You can\'t add owner role');
        }

        $roleExists = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->exists();

        if ($roleExists)
        {
            return ApiResponse::error('Role already exists');
        }

        CompanyUser::setCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('Role added successfully', [
            'role' => $role
        ]);
    }

    public function removeRole(Request $request, User $user, $role)
    {
        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;
        $companyId = $request->company_id;
        $role = CompanyRole::from($role); // Convert string to Enum

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t remove your own role');
        }

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User is not in the company');
        }

        if ($role === CompanyRole::OWNER)
        {
            return ApiResponse::error('You can\'t remove owner role');
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

    public function removeUser(Request $request, User $user)
    {
        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;
        $companyId = $request->company_id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t remove yourself');
        }

        $membership = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$membership)
        {
            return ApiResponse::error('User already is not in the company');
        }

        $userIsOwner = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', CompanyRole::OWNER)
            ->exists();

        if ($userIsOwner)
        {
            return ApiResponse::error('You can\'t remove owner');
        }

        CompanyUser::unsetCompanyUser($companyId, $userId);

        return ApiResponse::success('User deleted successfully');
    }
}
