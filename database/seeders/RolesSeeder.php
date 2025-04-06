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
        ]);

        Role::query()->create([
            'name' => 'PM',
            'is_default' => true,
        ]);
    }
}
