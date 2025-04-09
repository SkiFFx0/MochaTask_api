<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Status\StoreRequest;
use App\Models\Status;
use App\Models\StatusTeam;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $status = Status::query()
            ->where('name', $validated['name'])
            ->first();

        if (!$status)
        {
            $status = Status::query()->create($validated);
        }

        $statusId = $status->id;
        $teamId = $request->team_id;

        $statusExists = StatusTeam::query()
            ->where('status_id', $statusId)
            ->where('team_id', $teamId)
            ->exists();

        if ($statusExists)
        {
            return ApiResponse::error('Status already exists');
        }

        StatusTeam::setStatusTeam($statusId, $teamId);

        return ApiResponse::success('Status added successfully', [
            'status' => $status,
        ]);
    }

    public function destroy(Request $request, Status $status)
    {
        $teamId = $request->team_id;

        $status = StatusTeam::query()
            ->where('status_id', $status->id)
            ->where('team_id', $teamId)
            ->first();

        if (!$status)
        {
            return ApiResponse::error('Status not found');
        }

        $status->delete();

        return ApiResponse::success('Status deleted successfully');
    }
}
