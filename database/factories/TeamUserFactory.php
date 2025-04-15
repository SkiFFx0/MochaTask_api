<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamUser>
 */
class TeamUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::inRandomOrder()->first()?->id ?? Team::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'role' => $this->faker->randomElement(['backender', 'frontender', 'devops', 'designer']),
            'is_privileged' => $this->faker->boolean(5),
        ];
    }
}
