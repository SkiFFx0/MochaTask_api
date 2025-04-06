<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class InvitationController extends Controller
{
    public function generateInviteLink(Request $request)
    {
        $companyId = $request->company_id;

        if (!Company::query()->where('id', $companyId)->exists())
        {
            return ApiResponse::error('Company doesn\'t exist');
        }

        $link = URL::temporarySignedRoute(
            'invitation.accept',
            now()->addHour(), [
                'company_id' => $companyId,
            ]
        );

        return ApiResponse::success('Invitation created', [
            'link' => $link
        ]);
    }

    public function acceptInviteLink(Request $request)
    {
        if (!$request->hasValidSignature())
        {
            return ApiResponse::error('Invalid or expired invite link.');
        }

        $user = auth()->user();
        $userId = $user->id;
        $companyId = $request->company_id;

        $userInCompany = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if ($userInCompany)
        {
            return ApiResponse::error('You are already part of this company.');
        }

        CompanyUser::setCompanyUserRole($companyId, $userId, CompanyRole::MEMBER);

        $company = Company::find($companyId);

        return ApiResponse::success('Invitation accepted', [
            'company' => $company
        ]);
    }
}
