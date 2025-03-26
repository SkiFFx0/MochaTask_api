<?php

namespace Database\Seeders;

use App\Models\CompanyUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyUser::query()->create([
            'company_id' => 1,
            'user_id' => 1,
            'role' => 'owner',
        ]);

        CompanyUser::query()->create([
            'company_id' => 1,
            'user_id' => 2,
            'role' => 'admin',
        ]);
    }
}
