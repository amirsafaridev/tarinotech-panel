<?php

namespace Modules\Support\database\factories;

use App\Enums\Database\SampleMessage\MessageType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Support\app\Models\SampleMessage;

/**
 * @extends Factory
 */
class SampleMessageFactory extends Factory
{
    protected $model = SampleMessage::class;

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
