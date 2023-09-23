<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Project',
            'admin_id' => rand(1, 10),
            'price' => $this->faker->randomElement([10000, 50000, 600000, 80000, 10000000, 200000000]),
            'status' => $this->faker->randomElement(['pending', 'done', 'pay']),
            'created_at' => now()->addMonths(rand(1, 5))->startOfMonth(),
            'updated_at' => now()->addMonths(rand(1, 5))->startOfMonth(),
        ];
    }
}
