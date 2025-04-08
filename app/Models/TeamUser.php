<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamUser extends Pivot
{
    protected $table = 'team_user';

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'is_privileged',
    ];

    protected static function setTeamUserRole($teamId, $userId, $role)
    {
        return self::create([
            'team_id' => $teamId,
            'user_id' => $userId,
            'role' => $role,
            'is_privileged' => Role::where('name', $role)->first()?->is_privileged ?? false
        ]);
    }

    protected static function unsetTeamUserRole($teamId, $userId, $role)
    {
        return self::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->delete();
    }

    protected static function unsetTeamUser($teamId, $userId)
    {
        return self::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function scopePrivileged(Builder $query): void
    {
        $query->where('is_privileged', true);
    }
}
