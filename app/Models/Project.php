<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     *
     * Get users of the project
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'project_id', 'id');
    }
}
