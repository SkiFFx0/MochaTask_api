<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'company_id',
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

    /**
     * @return BelongsTo
     *
     * Get the company of the project
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return BelongsToMany
     *
     * Get the roles of the project
     */
    public function roles(): BelongsToMany
    {
        return $this->BelongsToMany(Role::class, 'roles', 'role_id', 'project_id');
    }
}
