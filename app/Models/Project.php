<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'company_id'
    ];

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
     * @return HasMany
     *
     * Get the teams of the project
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'project_id', 'id');
    }
}
