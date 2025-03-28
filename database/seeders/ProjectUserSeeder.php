<?php

namespace Database\Seeders;

use App\Models\ProjectUser;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->where('id', 1)->firstOrFail(['name']);

        $roleName = $role['name'];

        ProjectUser::setProjectUserRole(1, 2, $roleName);
    }
}
