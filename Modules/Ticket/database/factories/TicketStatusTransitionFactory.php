<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusTransition;

class TicketStatusTransitionFactory extends Factory
{
    protected $model = TicketStatusTransition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_status_id' => TicketStatus::factory(),
            'to_status_id' => TicketStatus::factory(),
            'days_until_transition' => $this->faker->numberBetween(1, 7),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
