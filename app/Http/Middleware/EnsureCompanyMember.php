<?php

namespace App\Http\Middleware;

use App\Models\ApiResponse;
use App\Models\Company;
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

        $company = $request->company;

        if (!$company instanceof Company)
        {
            $company = Company::query()->findOrFail($company);
        }

        $companyId = $company->id;

        $membership = CompanyUser::query()->where([
            ['company_id', $companyId],
            ['user_id', $userId]
        ])->exists();

        if (!$membership)
        {
            return ApiResponse::error('You are not member of this company');
        }

        return $next($request);
    }
}
