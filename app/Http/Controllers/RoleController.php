<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectRole;
use App\Models\Role;

class RoleController extends Controller
{
    public function store(StoreRequest $request, Company $company, Project $project)
    {
        $storeData = $request->validated();

        $roleName = $storeData['name'];

        $role = Role::query()->create([
            'name' => $roleName,
        ]);

        $projectId = $project->id;
        $roleId = $role->id;

        ProjectRole::setProjectRole($projectId, $roleId);

        return ApiResponse::success('Role created successfully.', [
            'role' => $role,
        ]);
    }
}
