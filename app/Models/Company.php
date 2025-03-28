<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany
     *
     * Get the users of the company
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user', 'company_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany
     *
     * Get the projects of the company
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'company_id', 'id');
    }
}
