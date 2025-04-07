<?php

namespace Database\Seeders;

use App\Enums\CompanyRole;
use App\Models\CompanyUser;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyUser::setCompanyUserRole(1, 1, CompanyRole::OWNER);
        CompanyUser::setCompanyUserRole(1, 2, CompanyRole::MEMBER);
        CompanyUser::setCompanyUserRole(1, 2, CompanyRole::ADMIN);
    }
}
