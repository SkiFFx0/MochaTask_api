<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleTeam;
use Illuminate\Database\Seeder;

class RoleTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleTeam::setRoleTeam(1, 1);
    }
}
