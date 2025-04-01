<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
    }
}
