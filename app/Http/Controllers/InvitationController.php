<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Http\Requests\Invitation\CreateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class InvitationController extends Controller
{
    public function generateInviteLink(Company $company)
    {
        $companyId = $company->id;

        if (!Company::query()->where('id', $companyId)->exists())
        {
            return ApiResponse::error('Company doesn\'t exist');
        }

        $link = URL::temporarySignedRoute(
            'invitation.accept',
            now()->addHour(), [
            'company_id' => $companyId,
            'role' => CompanyRole::MEMBER
        ]);

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

        $companyId = $request->query('company_id');
        $userId = auth()->user()->id;
        $role = $request->query('role');

        if (CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists())
        {
            return ApiResponse::error('You are already part of this company.');
        }

        // Adding user to a company
        CompanyUser::setCompanyUserRole($companyId, $userId, CompanyRole::MEMBER);
//        CompanyUser::create([
//            'company_id' => $companyId,
//            'user_id' => $userId,
//            'role' => $role
//        ]);

        return ApiResponse::success('Invitation accepted');
    }

    public function generateInviteToken(CreateRequest $request)
    {
        $request->validated();
dd($request->all());
        $token = Crypt::encryptString(json_encode($request->all()));

        return ApiResponse::success('Invitation created', [
            'invitation' => $token
        ]);
    }

    public function acceptInviteToken($token)
    {
        $tokenData = json_decode(Crypt::decryptString($token));
        $companyId = $tokenData->company_id;
        $role = CompanyRole::from($tokenData->role); // Convert string to Enum
        $user = auth()->user();
        $userId = $user->id;

        CompanyUser::setCompanyUserRole($companyId, $userId, $role);

        return ApiResponse::success('You have successfully accepted this invitation.', [
            'invitation' => $token,
            'user' => $user
        ]);
    }

    public function declineInviteToken($token)
    {
        return ApiResponse::error('Unfinished function');
    }
}
