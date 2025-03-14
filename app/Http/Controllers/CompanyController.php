<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;

class CompanyController extends Controller
{
    public function store(StoreRequest $request)
    {
        $storeData = $request->validated();

        $company = Company::query()->create($storeData);

        return ApiResponse::success('Company created successfully', [
            'company' => $company
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
