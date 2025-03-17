<?php

namespace App\Http\Middleware;

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
        $company = $request->route('company');

        $companyUser = CompanyUser::query()
            ->where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$companyUser)
        {
            return response()->json(['error' => 'Unauthorized. You are not part of this company.'], 403);
        }

        $request->attributes->set('companyRole', $companyUser->role);

        return $next($request);
    }
}
