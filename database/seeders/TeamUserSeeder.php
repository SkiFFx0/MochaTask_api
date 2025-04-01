<?php

namespace Database\Seeders;

use App\Models\TeamUser;
use App\Models\Role;
use Illuminate\Database\Seeder;

class TeamUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->where('id', 1)->firstOrFail(['name']);

        $roleName = $role['name'];

        TeamUser::setTeamUserRole(1, 2, $roleName);
    }
}
