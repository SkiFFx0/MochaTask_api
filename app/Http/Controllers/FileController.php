<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\File;
use App\Models\Task;

class FileController extends Controller
{
    public function destroy(File $file)
    {
        $file->delete();

        return ApiResponse::success('File deleted successfully');
    }
}
