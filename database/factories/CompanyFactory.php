<?php

namespace Database\Factories;

use App\Enums\Database\Company\CompanyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'identify' => $this->faker->numerify('22#####'),
            'register_id' => $this->faker->numerify('33#####'),
            'type' => CompanyType::getRandomValue(),
            'user_id' => rand(1, 50),
        ];
    }
}
