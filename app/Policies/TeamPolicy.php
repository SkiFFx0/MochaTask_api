<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;

class TeamPolicy
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
    public function view(User $user, Team $team): bool
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
    public function update(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * Check user privileges for managing team
     */
    public function manage(User $user, Team $team): bool
    {
        $request = request();

        $userId = $user->id;
        $teamId = $team->id;

        $isPrivileged = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->privileged()
            ->exists();

        $companyPrivileged = $request->get('isCompanyPrivileged', false);

        return $isPrivileged || $companyPrivileged;
    }
}
