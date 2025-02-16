<?php

namespace Modules\Auth\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\app\Models\Login;

class LoginFactory extends Factory
{
    protected $model = Login::class;

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
