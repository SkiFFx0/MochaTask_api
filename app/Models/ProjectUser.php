<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    protected $table = 'project_user';

    protected $fillable = [
        'project_id',
        'user_id',
    ];

    protected static function setProjectUser($projectId, $userId)
    {
        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
    }

    protected static function unsetProjectUser($projectId, $userId)
    {
        return self::query()
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->delete();
    }
}
