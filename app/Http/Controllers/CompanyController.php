<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function store(StoreRequest $request)
    {dd(request());
        $validated = $request->validated();

        $company = DB::transaction(function () use ($request, $validated)
        {
            $company = Company::query()->create($validated);
            $companyId = $company->id;
            $userId = auth()->user()->id;
            $role = CompanyRole::OWNER;

            CompanyUser::setCompanyUserRole($companyId, $userId, $role);

            return $company;
        });

        return ApiResponse::success('Company created successfully', [
            'company' => $company,
        ]);
    }

    public function update(UpdateRequest $request, Company $company)
    {dd($request);
        $validated = $request->validated();

        $company->update($validated);

        return ApiResponse::success('Company updated successfully', [
            'company' => $company
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return ApiResponse::success('Company deleted successfully');
    }
}
