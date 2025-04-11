<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $taskId = $request->task_id;
        $task = Task::find($taskId);
        $taskName = $task->name;

        $taskFiles = $task->files()->get();

        return ApiResponse::success("Files inside $taskName task", [
            'Task Files' => $taskFiles
        ]);
    }

    public function destroy(File $file)
    {
        $file->delete();

        return ApiResponse::success('File deleted successfully');
    }
}
