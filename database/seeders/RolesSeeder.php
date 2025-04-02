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
        Role::query()->insert([
            ['name' => 'admin', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PM', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
