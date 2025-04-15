<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\CompanyUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyPrivileges
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->user()->id;
        $companyId = $request->company === null ? $request->company_id : $request->company->id;

        $userInCompanyPrivileged = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->privileged()
            ->first();

        if (!$userInCompanyPrivileged)
        {
            return ApiResponse::error('You are not privileged in this company to perform this action');
        }

        $request->attributes->set('company_privileged', true);

        return $next($request);
    }
}
