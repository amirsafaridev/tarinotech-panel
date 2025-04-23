<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketTransition;

class TicketTransitionFactory extends Factory
{
    protected $model = TicketTransition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_status_id' => TicketStatusFactory::new(),
            'to_status_id' => TicketStatusFactory::new(),
            'event_id' => TicketEventFactory::new(),
            'days_trigger' => $this->faker->numberBetween(1, 30),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
