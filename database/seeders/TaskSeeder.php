<?php

namespace Database\Seeders;

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
            'status_id' => 1,
            'user_id' => 2,
            'team_id' => 1,
        ]);
    }
}
