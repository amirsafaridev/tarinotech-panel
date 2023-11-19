<?php

namespace Modules\Admin\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use function bcrypt;

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
            'mobile_company' => $this->faker->numerify('09#########'),
            'number_company' => $this->faker->numerify('021######'),
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
