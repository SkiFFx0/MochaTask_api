<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Enums\ProjectRole;
use App\Http\Requests\Company\AddRoleRequest;
use App\Http\Requests\Company\AddUserRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;

class MemberController extends Controller
{
    public function addUser(AddUserRequest $request, Company $company)
    {
        $request->validated();

        $companyId = $company->id;

        $userId = $request['user_id'];

        $role = CompanyRole::MEMBER;

        if (CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists())
        {
            return ApiResponse::error('User already in the company');
        }

        // Assign user to a company
        CompanyUser::setCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('User added successfully', [
            'company' => $company
        ]);
    }

    public function addRole(AddRoleRequest $request, Company $company, User $user)
    {
        $request->validated();

        $companyId = $company->id;
        $userId = $user->id;
        $role = CompanyRole::from($request->role); // Convert string to Enum

        if (!CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists())
        {
            return ApiResponse::error('User is not in the company');
        }

        if (CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->exists())
        {
            return ApiResponse::error('User already has this role');
        }

        // Add role to a user
        CompanyUser::setCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('Role added successfully', [
            'company' => $company
        ]);
    }

    public function removeRole(Company $company, User $user, $role)
    {
        $companyId = $company->id;
        $userId = $user->id;
        $role = CompanyRole::from($role); // Convert string to Enum

        if (!CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists())
        {
            return ApiResponse::error('User is not in the company');
        }

        if (!CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->exists())
        {
            return ApiResponse::error('User doesn\'t have this role');
        }

        CompanyUser::unsetCompanyUserRole($company, $user, $role);

        return ApiResponse::success('Role removed successfully', [
            'company' => $company
        ]);
    }

    public function removeUser(Company $company, User $user)
    {
        $companyId = $company->id;
        $userId = $user->id;

        if (!CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists())
        {
            return ApiResponse::error('User already is not in the company');
        }

        CompanyUser::unsetCompanyUser($company, $user);

        return ApiResponse::success('User deleted successfully');
    }
}
