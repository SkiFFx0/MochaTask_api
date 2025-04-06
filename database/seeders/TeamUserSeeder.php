<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TeamUser;
use Illuminate\Database\Seeder;

class TeamUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamUser::setTeamUserRole(1, 2, 'admin');
    }
}
