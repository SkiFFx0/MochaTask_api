<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreRequest $request)
    {
        $storeData = $request->validated();

        $company = Company::query()->create($storeData);
        $companyId = $company->id;
        $user = auth()->user();
        $userId = $user->id;

        CompanyUser::query()->create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'role' => CompanyRole::OWNER
        ]);

        return ApiResponse::success('Company created successfully', [
            'company' => $company,
        ]);
    }

    public function update(UpdateRequest $request, Company $company)
    {
        $updateData = $request->validated();

        $company->update($updateData);

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
