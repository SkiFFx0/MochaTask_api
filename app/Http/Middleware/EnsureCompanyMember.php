<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\CompanyUser;
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
        $user = auth()->user();
        $userId = $user->id;
        $companyId = $request->company === null ? $request->company_id : $request->company->id;

        $userInCompany = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$userInCompany)
        {
            return ApiResponse::error('You are not member of this company');
        }

        return $next($request);
    }
}
