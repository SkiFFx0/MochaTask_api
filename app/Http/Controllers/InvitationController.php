<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Http\Requests\Invitation\CreateRequest;
use App\Models\ApiResponse;
use App\Models\CompanyUser;
use App\Models\Invitation;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Crypt;

class InvitationController extends Controller
{
    public function create(CreateRequest $request)
    {;
        $request->validated();

        $token = Crypt::encryptString(json_encode($request->all()));

        return ApiResponse::success('Invitation created', [
            'invitation' => $token
        ]);
    }

    public function accept($token)
    {
        $tokenData = json_decode(Crypt::decryptString($token));
        $companyId = $tokenData->company_id;
        $role = CompanyRole::from($tokenData->role); // Convert string to Enum
        $user = auth()->user();

        CompanyUser::assignUserToCompanyAndAddRole($companyId, $user->id, $role);

        return ApiResponse::success('You have successfully accepted this invitation.', [
            'invitation' => $token,
            'user' => $user
        ]);
    }

    public function decline($token)
    {
        return ApiResponse::error('Unfinished function');
    }
}
