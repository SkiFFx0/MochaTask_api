<?php

namespace Database\Seeders;

use App\Models\StatusTeam;
use Illuminate\Database\Seeder;

class StatusTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusTeam::assignDefaultStatuses(1);
    }
}
