<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketStatus;

class TicketStatusFactory extends Factory
{
    protected $model = TicketStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'color' => $this->faker->hexColor(),
            'description' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
