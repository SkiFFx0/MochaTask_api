<?php

namespace App\Http\Middleware;

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
        $userId = auth()->user()->id;
        $companyId = $request->company_id;

        $companyUser = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->first();

        if (!$companyUser)
        {
            return response()->json(['error' => 'Unauthorized. You are not part of this company.'], 403);
        }

        $request->attributes->set('companyRole', $companyUser->role);

        return $next($request);
    }
}
