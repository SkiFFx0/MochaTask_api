<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'name'
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_status', 'status_id', 'team_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}
