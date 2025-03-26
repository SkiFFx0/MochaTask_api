<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    protected $table = 'project_user';

    protected $fillable = [
        'project_id',
        'user_id',
        'role'
    ];

    protected static function setProjectUserRole($projectId, $userId, $role)
    {
        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role' => $role,
        ]);
    }

    protected static function unsetProjectUserRole($projectId, $userId, $role)
    {
        return self::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('role', $role)
            ->delete();
    }

    protected static function unsetProjectUser($projectId, $userId)
    {
        return self::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->delete();
    }
}
