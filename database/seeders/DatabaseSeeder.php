<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UserSeeder::class]);

        $this->call([CompanySeeder::class]);

        $this->call([CompanyUserSeeder::class]);

        $this->call([ProjectSeeder::class]);

        $this->call([TeamSeeder::class]);

        $this->call([RolesSeeder::class]);

        $this->call([TeamUserSeeder::class]);

        $this->call([RoleTeamSeeder::class]);

        $this->call([TaskSeeder::class]);
    }
}
