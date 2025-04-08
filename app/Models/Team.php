<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'project_id'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

//    public function roles(): BelongsToMany
//    {
//        return $this->belongsToMany(Role::class, 'role_team', 'team_id', 'role_id')
//            ->withTimestamps();
//    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'team_id', 'id');
    }
}
