<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyUser>
 */
class CompanyUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = $this->faker->randomElement(['owner', 'admin', 'member']);

        return [
            'company_id' => Company::inRandomOrder()->first()?->id ?? Company::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'role' => $role,
            'is_privileged' => in_array($role, ['owner', 'admin']),
        ];
    }
}
