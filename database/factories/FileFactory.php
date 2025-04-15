<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'size' => $this->faker->randomNumber(),
            'path' => $this->faker->imageUrl(),
            'task_id' => Task::inRandomOrder()->first()?->id ?? Task::factory(),
        ];
    }
}
