<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Task 1 description',
            'team_id' => 1,
        ]);

        File::query()->create([
            'name' => 'File 1',
            'size' => 1024,
            'path' => 'tasks/file-1',
            'task_id' => 1,
        ]);
    }
}
