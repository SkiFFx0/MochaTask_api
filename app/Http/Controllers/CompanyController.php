<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Http\Requests\Company\AddUserRequest;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $companies = Company::all();

        return ApiResponse::success('Companies list', [
            'companies' => $companies
        ]);
    }

    public function show($id)
    {
        $company = Company::query()->find($id);

        if (!$company)
        {
            return ApiResponse::error('Company not found', null, 404);
        }

        return ApiResponse::success('Company', [
            'company' => $company
        ]);
    }

    public function store(StoreRequest $request)
    {
        $storeData = $request->validated();

        $company = Company::query()->create($storeData);
        $user = auth()->user();

        CompanyUser::query()->create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'role' => 'owner'
        ]);

        return ApiResponse::success('Company created successfully', [
            'company' => $company
        ]);
    }

    public function update(UpdateRequest $request, Company $company)
    {
        $this->authorize('manage', $company);

        $updateData = $request->validated();

        $company->update($updateData);

        return ApiResponse::success('Company updated successfully', [
            'company' => $company
        ]);
    }

    public function destroy(Company $company)
    {
        $this->authorize('manage', $company);

        $company->delete();

        return ApiResponse::success('Company deleted successfully');
    }

//    public function addUserWithRole(AddUserRequest $request, Company $company)
//    {
//        $request->validated();
//
//        $role = CompanyRole::from($request->role); // Convert string to Enum
//
//        // Assign user to a company
//        CompanyUser::assignUserToCompanyAndAddRole($company->id, $request->user_id, $role);
//
//        return ApiResponse::success('User added successfully', [
//            'company' => $company
//        ]);
//    }
//
//    public function removeUser(Company $company, User $user)
//    {
//        CompanyUser::removeUserFromCompany($company, $user);
//
//        return ApiResponse::success('User deleted successfully');
//    }
}
