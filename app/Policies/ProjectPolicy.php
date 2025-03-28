<?php

namespace App\Policies;

use App\Enums\CompanyRole;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }

    public function manage(User $user, Project $project): bool
    {
        $userId = $user->id;
        $projectId = $project->id;

        $isPrivileged = ProjectUser::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->privileged()
            ->exists();

        return $isPrivileged;
    }
}
