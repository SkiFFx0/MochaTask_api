<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'owner',
            'last_name' => 'owner',
            'middle_name' => 'owner',
            'email' => 'owner@owner.com',
            'password' => 'password',
        ]);

        User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'middle_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);
    }
}
