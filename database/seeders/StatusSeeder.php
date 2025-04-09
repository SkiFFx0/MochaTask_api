<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::query()->create([
            'name' => 'TO DO',
            'is_default' => true,
        ]);

        Status::query()->create([
            'name' => 'IN PROGRESS',
            'is_default' => true,
        ]);

        Status::query()->create([
            'name' => 'DONE',
            'is_default' => true,
        ]);
    }
}
