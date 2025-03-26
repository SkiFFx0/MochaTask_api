<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * @return BelongsToMany
     *
     * Get users of the project
     */
    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'project_user', 'user_id', 'project_id')
            ->withTimestamps();
    }
}
