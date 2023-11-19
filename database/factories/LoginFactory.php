<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\Admin;

class LoginFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_type' => $this->faker->randomElement([Admin::class]),
            'user_id' => rand(1, 10),
            'login_at' => $this->faker->dateTime,
            'agent' => $this->faker->userAgent,
            'ip' => $this->faker->ipv4,
        ];
    }
}
