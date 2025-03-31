<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectRole;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function store(StoreRequest $request, Company $company, Project $project)
    {
        $storeData = $request->validated();

        $projectId = $project->id;

        $role = DB::transaction(function () use ($storeData, $projectId)
        {
            $role = Role::query()->create($storeData);

            $roleId = $role->id;

            ProjectRole::setProjectRole($projectId, $roleId);

            return $role;
        });

        return ApiResponse::success('Role created successfully.', [
            'role' => $role,
        ]);
    }

    public function update(UpdateRequest $request, Company $company, Project $project, Role $role)
    {
        $updateData = $request->validated();

        $roleId = $role->id;

        Role::query()->where('id', $roleId)->update($updateData);

        $role = Role::query()->findOrFail($roleId);

        return ApiResponse::success('Role updated successfully.', [
            'role' => $role,
        ]);
    }

    public function destroy(Company $company, Project $project, Role $role)
    {
        $roleId = $role->id;

        Role::query()->where('id', $roleId)->delete();

        return ApiResponse::success('Role deleted successfully.');
    }
}
