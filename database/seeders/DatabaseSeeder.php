<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call([UserSeeder::class]);

//        $this->call([CompanySeeder::class]);

//        $this->call([CompanyUserSeeder::class]);

//        $this->call([ProjectSeeder::class]);

//        $this->call([TeamSeeder::class]);

//        $this->call([TeamUserSeeder::class]);

        $this->call([StatusSeeder::class]);

//        $this->call([StatusTeamSeeder::class]);

//        $this->call([TaskSeeder::class]);

//        $this->call([FileSeeder::class]);
    }
}
