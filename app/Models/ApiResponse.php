<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class ApiResponse extends Model //TODO this should not be a model
{
    public static function success($message = "OK", $data = null, $code = 200)
    {
        if (!$data)
        {
            $data = new stdClass();
        }
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => 1,
        ], $code);
    }

    public static function error($message = "ERROR", $data = null, $code = 500)
    {
        if (!$data)
        {
            $data = new stdClass();
        }
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => 0,
        ], $code);
    }
}
