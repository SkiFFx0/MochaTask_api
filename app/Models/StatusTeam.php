<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StatusTeam extends Pivot
{
    use HasFactory;

    protected $table = 'status_team';

    protected $fillable = [
        'status_id',
        'team_id',
    ];

    public static function assignDefaultStatuses($teamId)
    {
        $defaultStatuses = Status::where('is_default', true)->get();

        foreach ($defaultStatuses as $status) {
            self::create([
                'status_id' => $status->id,
                'team_id' => $teamId,
            ]);
        }
    }

    public static function setStatusTeam($statusId, $teamId)
    {
        StatusTeam::query()->create([
            'status_id' => $statusId,
            'team_id' => $teamId,
        ]);
    }
}
