<?php

namespace Database\Seeders;

use App\Models\RoleTeam;
use Illuminate\Database\Seeder;

class RoleTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleTeam::query()->insert([
            ['role_id' => 1, 'team_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'team_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
