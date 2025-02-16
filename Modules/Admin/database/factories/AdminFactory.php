<?php

namespace Modules\Admin\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\Admin;

/**
 * @extends Factory
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'job_title_id' => rand(1, 10),
            'mobile' => $this->faker->numerify('09#########'),
            'mobile_company' => $this->faker->numerify('09#########'),
            'number_company' => $this->faker->numerify('021######'),
            'email' => $this->faker->unique()->email,
            'password' => bcrypt('12345678'),
        ];
    }
}
