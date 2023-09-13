<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'mobile' => $this->faker->numerify('09#########'),
            'email' => $this->faker->unique()->email,
            'password' => bcrypt('12345678'),
            'has_access' => true,
            'avatar' => null,
            'dob' => $this->faker->date,
            'start_cooperation' => $this->faker->date,
            'start_last_contract' => $this->faker->date,
            'end_last_contract' => $this->faker->date,
            'resume' => 'Resume',
            'description' => 'Description',

        ];
    }
}
