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
        $companyId = $request->company === null ? $request->company_id : $request->company->id;
        $companyPrivilegedIds = $request->attributes->get('company_privileged_ids');

        if (!in_array($companyId, $companyPrivilegedIds))
        {
            return ApiResponse::error('You are not privileged in this company to perform this action');
        }

        return $next($request);
    }
}
