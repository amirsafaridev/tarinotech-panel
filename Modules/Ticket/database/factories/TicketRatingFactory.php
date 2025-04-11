<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Models\TicketRating;
use Modules\User\app\Models\User;

class TicketRatingFactory extends Factory
{
    protected $model = TicketRating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_id' => function () {
                return Chat::where('type', 'ticket')->inRandomOrder()->first()?->id ??
                       Chat::factory()->create(['type' => 'ticket'])->id;
            },
            'user_type' => User::class,
            'user_id' => function () {
                return User::inRandomOrder()->first()?->id ??
                       User::factory()->create()->id;
            },
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->boolean(70) ? $this->faker->paragraph() : null,
        ];
    }

    /**
     * Indicate that the rating is excellent.
     */
    public function excellent()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 5,
                'comment' => $this->faker->boolean(80) ? $this->faker->paragraph() : null,
            ];
        });
    }

    /**
     * Indicate that the rating is poor.
     */
    public function poor()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => $this->faker->numberBetween(1, 2),
                'comment' => $this->faker->boolean(90) ? $this->faker->paragraph() : null,
            ];
        });
    }
}
