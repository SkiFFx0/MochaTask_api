<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany
     *
     * Get the projects of the role
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_roles', 'role_id', 'project_id');
    }
}
