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

        $query = DB::transaction(function () use ($request, $storeData, $teamId)
        {
            $task = Task::query()->create([
                'name' => $storeData['name'],
                'description' => $storeData['description'],
                'team_id' => $teamId,
            ]);

            $attachment = null;

            if ($request->hasFile('files'))
            {
                foreach ($request->file('files') as $file)
                {
                    $path = $file->store('tasks', 'public');
                    $name = $file->getClientOriginalName();
                    $size = $file->getSize();

                    $attachment = $task->files()->create([
                        'name' => $name,
                        'size' => $size,
                        'path' => $path,
                    ]);
                }
            }

            return (object)[
                'task' => $task,
                'attachment' => $attachment
            ];
        });

        return ApiResponse::success('Task created successfully', [
            'task' => $query->task,
            'attachment' => $query->attachment,
        ]);
    }

    public function update(UpdateRequest $request, Company $company, Project $project, Team $team, Task $task)
    {
        $updateData = $request->validated();

        $query = DB::transaction(function () use ($request, $task, $updateData)
        {
            $task->update($updateData);

            $attachment = null;

            if ($request->hasFile('files'))
            {
                foreach ($request->file('files') as $file)
                {
                    $path = $file->store('tasks', 'public');
                    $name = $file->getClientOriginalName();
                    $size = $file->getSize();

                    $attachment = $task->files()->create([
                        'name' => $name,
                        'size' => $size,
                        'path' => $path,
                    ]);
                }
            }

            return (object)[
                'task' => $task,
                'attachment' => $attachment
            ];
        });

        return ApiResponse::success('Task updated successfully', [
            'task' => $query->task,
            'attachment' => $query->attachment,
        ]);
    }

    public function destroy(Company $company, Project $project, Team $team, Task $task) //TODO add ability to delete only the file, not whole task
    {
        $task->delete();

        return ApiResponse::success('Task deleted successfully');
    }
}
