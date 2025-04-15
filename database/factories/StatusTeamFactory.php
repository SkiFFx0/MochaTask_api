<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatusTeam>
 */
class StatusTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_id' => Status::inRandomOrder()->first()?->id ?? Status::factory(),
            'team_id' => Team::inRandomOrder()->first()?->id ?? Team::factory(),
        ];
    }
}
