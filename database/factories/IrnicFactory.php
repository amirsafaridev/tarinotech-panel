<?php

namespace Database\Factories;

use App\Enums\Database\User\IrnicStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends Factory
 */
class IrnicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => IrnicStatus::getRandomValue(),
            'identify' => $this->faker->numerify('IR####'),
            'password' => Crypt::encrypt('12345678'),
            'user_id' => rand(1, 50),
        ];
    }
}
