<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Task\StatusChangeRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Status;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $teamId = $request->team_id;
        $team = Team::find($teamId);
        $teamName = $team->name;

        $teamTasks = $team->tasks()->get();

        return ApiResponse::success("Tasks inside $teamName team", [
            'Team Tasks' => $teamTasks
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $teamId = $request->team_id;

        $statusId = Status::query()
            ->where('name', $validated['status'])
            ->value('id');

        $userInTeam = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if (!$userInTeam)
        {
            return ApiResponse::error('Invalid user');
        }

        $query = DB::transaction(function () use ($request, $validated, $teamId, $statusId)
        {
            $task = Task::query()->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'status_id' => $statusId,
                'team_id' => $teamId,
                'user_id' => $validated['user_id'],
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

        $teamId = $request->team_id;

        $userInTeam = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if (!$userInTeam)
        {
            return ApiResponse::error('Invalid user');
        }

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

    public function changeStatus(StatusChangeRequest $request, Task $task)
    {
        $validated = $request->validated();

        $userId = auth()->user()->id;

        $companyPrivileged = $request->attributes->get('company_privileged');

        if (!$task->where('user_id', $userId)->exists() && !$companyPrivileged)
        {
            return ApiResponse::error('You can\'t change task status');
        }

        $statusId = Status::query()
            ->where('name', $validated['status'])
            ->value('id');

        $task->update([
            'status_id' => $statusId,
        ]);

        return ApiResponse::success('Task status updated successfully', [
            'task' => $task,
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return ApiResponse::success('Task deleted successfully');
    }
}
