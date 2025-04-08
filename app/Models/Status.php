<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'statuses';

    protected $fillable = [
        'name'
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_status', 'status_id', 'team_id');
    }
}
