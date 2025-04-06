<?php

namespace App\Http\Middleware;

use App\Models\ApiResponse;
use App\Models\CompanyUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyPrivileges
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $userId = $user->id;
        $companyId = $request->company === null ? $request->company_id : $request->company->id;

        $userInCompanyPrivileged = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->privileged()
            ->exists();

        if (!$userInCompanyPrivileged)
        {
            return ApiResponse::error('You are not privileged in this company to perform this action');
        }
        return $next($request);
    }
}
