<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\Role;
use App\Models\RoleTeam;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $role = DB::transaction(function () use ($request, $validated)
        {
            $teamId = $request->team_id;

            $role = Role::query()->create($validated);
            $roleId = $role->id;

            RoleTeam::setRoleTeam($roleId, $teamId);

            return $role;
        });

        return ApiResponse::success('Role created successfully.', [
            'role' => $role,
        ]);
    }

    public function update(UpdateRequest $request, Role $role)
    {
        $validated = $request->validated();

        $role->update($validated);

        return ApiResponse::success('Role updated successfully.', [
            'role' => $role,
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return ApiResponse::success('Role deleted successfully.');
    }
}
