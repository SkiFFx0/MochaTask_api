<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleTeam extends Pivot
{
    protected $table = 'role_team';

    protected $fillable = [
        'role_id',
        'team_id'
    ];

    protected static function assignDefaultRoles($teamId)
    {
        // Fetch all roles where is_default column is true
        $defaultRoles = Role::where('is_default', true)->get();

        // Loop over each default role and attach it to the provided team
        foreach ($defaultRoles as $role) {
            self::create([
                'role_id' => $role->id,
                'team_id' => $teamId,
            ]);
        }
    }

    protected static function unsetRoleTeam($roleId, $teamId)
    {
        return self::query()
            ->where('role_id', $roleId)
            ->where('team_id', $teamId)
            ->delete();
    }
}
