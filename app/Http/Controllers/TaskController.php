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
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $teamId = $request->team_id;

        $query = DB::transaction(function () use ($request, $validated, $teamId)
        {
            $task = Task::query()->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
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

    public function update(UpdateRequest $request, Task $task)
    {
        $validated = $request->validated();

        $query = DB::transaction(function () use ($request, $task, $validated)
        {
            $task->update($validated);

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

    public function destroy(Task $task)
    {
        $task->delete();

        return ApiResponse::success('Task deleted successfully');
    }
}
