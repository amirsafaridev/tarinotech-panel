<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketPriority;

class TicketPriorityFactory extends Factory
{
    protected $model = TicketPriority::class;

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
            'should_notify' => $this->faker->boolean(),
            'level' => $this->faker->numberBetween(1, 4),
        ];
    }
}
