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
    public function invite(Request $request)
    {
        $companyId = $request->company_id;

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

    public function accept(Request $request)
    {
        if (!$request->hasValidSignature())
        {
            return ApiResponse::error('Invalid or expired invite link.');
        }

        $userId = auth()->user()->id;
        $companyId = $request->company_id;

        $userInCompany = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

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
