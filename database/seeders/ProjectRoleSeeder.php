<?php

namespace Database\Seeders;

use App\Models\ProjectRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectRole::query()->create([
            'project_id' => 1,
            'role_id' => 1,
        ]);

        ProjectRole::query()->create([
            'project_id' => 1,
            'role_id' => 2,
        ]);
    }
}
