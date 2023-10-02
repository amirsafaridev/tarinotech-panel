<?php

namespace Database\Factories;

use App\Enums\Database\SampleMessage\MessageType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class SampleMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'type' => MessageType::getRandomValue(),
        ];
    }
}
