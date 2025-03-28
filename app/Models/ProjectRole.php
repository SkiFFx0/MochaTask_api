<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectRole extends Pivot
{
    protected $table = 'project_role';

    protected $fillable = [
        'project_id',
        'role_id',
    ];

    protected static function setProjectRole($projectId, $roleId)
    {
        return self::create([
            'project_id' => $projectId,
            'role_id' => $roleId,
        ]);
    }

    protected static function unsetProjectRole($projectId, $role)
    {
        return self::query()
            ->where('project_id', $projectId)
            ->where('role', $role)
            ->delete();
    }
}
