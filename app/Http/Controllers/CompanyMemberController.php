<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\CompanyMember\EditRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyMemberController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->company_id;
        $company = Company::find($companyId);
        $companyName = $company->name;

        $companyMembers = $company->users()->get();

        return ApiResponse::success("Members inside $companyName company", [
            'Company members' => $companyMembers
        ]);
    }

    public function editRole(EditRequest $request, User $user)
    {
        $validated = $request->validated();

        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t edit your own role', null, 403);
        }

        $companyId = $request->company_id;

        $userInCompany = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        if (!$userInCompany)
        {
            return NotFoundHttpException::class;
        }

        $role = CompanyRole::from($validated['role']); // Convert string to Enum

        if ($role === CompanyRole::OWNER)
        {
            return ApiResponse::error('You can\'t add "owner" role', null, 422);
        }

        $companyUser = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        $companyUser->update([
            'role' => $role,
            'is_privileged' => $role === CompanyRole::ADMIN,
        ]);

        return ApiResponse::success('Role updated successfully', [
            'role' => $role
        ]);
    }

    public function removeUser(Request $request, User $user)
    {
        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t remove yourself', null, 403);
        }

        $companyId = $request->company_id;

        $userInCompany = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        if (!$userInCompany)
        {
            return NotFoundHttpException::class;
        }

        $userIsOwner = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->where('role', CompanyRole::OWNER)
            ->exists();

        if ($userIsOwner)
        {
            return ApiResponse::error('You can\'t remove owner', null, 422);
        }

        CompanyUser::unsetCompanyUser($companyId, $userId);

        return ApiResponse::success('User deleted successfully');
    }
}
