<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyMember
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->user()->id;
        $companyId = $request->attributes->get('company_id');

        $userInCompany = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        if (!$userInCompany)
        {
            return ApiResponse::error('You are not member of this company', null, 403);
        }

        return $next($request);
    }
}
