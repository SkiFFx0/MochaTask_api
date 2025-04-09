<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::query()->create([
            'name' => 'File 1',
            'size' => 1024,
            'path' => 'tasks/file-1',
            'task_id' => 1,
        ]);
    }
}
