<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamUser extends Pivot
{
    use SoftDeletes;

    protected $table = 'team_user';

    protected $fillable = [
        'team_id',
        'user_id',
        'role'
    ];

    protected static function setTeamUserRole($teamId, $userId, $role)
    {
        return self::create([
            'team_id' => $teamId,
            'user_id' => $userId,
            'role' => $role,
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
        $query->whereIn('role', ['admin', 'PM']); //TODO make dynamic fetching of "is_privileged"
    }
}
