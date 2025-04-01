<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(StoreRequest $request, Company $company, Project $project, Team $team)
    {
        $storeData = $request->validated();
        $teamId = $team->id;

        $task = DB::transaction(function () use ($company, $project, $teamId, $storeData)
        {
            $task = Task::query()->create([
                'name' => $storeData['name'],
                'description' => $storeData['description'],
                'team_id' => $teamId,
            ]);

            //TODO attachments?

            return $task;
        });

        return ApiResponse::success('Task created successfully', [
            'task' => $task
        ]);
    }

    public function update(UpdateRequest $request, Company $company, Project $project, Team $team, Task $task)
    {
        $updateData = $request->validated();

        $task->update($updateData);

        return ApiResponse::success('Task updated successfully', [
            'task' => $task
        ]);
    }

    public function destroy(Company $company, Project $project, Team $team, Task $task)
    {
        $task->delete();

        return ApiResponse::success('Task deleted successfully');
    }
}
