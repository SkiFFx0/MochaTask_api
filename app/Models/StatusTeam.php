<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StatusTeam extends Pivot
{
    protected $table = 'status_team';

    protected $fillable = [
        'status_id',
        'team_id',
    ];

    public static function setStatusTeam($statusId, $teamId)
    {
        StatusTeam::query()->create([
            'status_id' => $statusId,
            'team_id' => $teamId,
        ]);
    }
}
