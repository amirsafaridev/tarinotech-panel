<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Enums\TriggerEnum;
use Modules\Ticket\app\Models\TicketEvent;

class TicketEventFactory extends Factory
{
    protected $model = TicketEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $triggers = TriggerEnum::getValues();

        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'trigger' => $this->faker->randomElement($triggers),
        ];
    }
}
