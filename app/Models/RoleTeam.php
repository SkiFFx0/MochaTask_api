<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleTeam extends Pivot
{
    use SoftDeletes;

    protected $table = 'role_team';

    protected $fillable = [
        'role_id',
        'team_id'
    ];

    protected static function setRoleTeam($roleId, $teamId)
    {
        return self::create([
            'role_id' => $roleId,
            'team_id' => $teamId,
        ]);
    }

    protected static function unsetRoleTeam($roleId, $teamId)
    {
        return self::query()
            ->where('role_id', $roleId)
            ->where('team_id', $teamId)
            ->delete();
    }
}
