<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name'
    ];

    /**
     * @return BelongsToMany
     *
     * Get the teams of the role
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'role_team', 'role_id', 'team_id')
            ->withTimestamps();
    }
}
