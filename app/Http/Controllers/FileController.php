<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function destroy(Company $company, Project $project, Team $team, Task $task)
    {
        $taskId = $task->id;//TODO


    }
}
