<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create([
            'name' => 'admin',
            'is_default' => true,
            'is_privileged' => true,
        ]);

        Role::query()->create([
            'name' => 'PM',
            'is_default' => false,
            'is_privileged' => true,
        ]);

        Role::query()->create([
            'name' => 'backender',
            'is_default' => false,
            'is_privileged' => false,
        ]);

        Role::query()->create([
            'name' => 'frontender',
            'is_default' => false,
            'is_privileged' => false,
        ]);
    }
}
