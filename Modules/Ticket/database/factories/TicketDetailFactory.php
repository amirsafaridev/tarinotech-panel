<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Models\TicketDetail;
use Modules\Ticket\app\Models\TicketPriority;
use Modules\Ticket\app\Models\TicketStatus;

class TicketDetailFactory extends Factory
{
    protected $model = TicketDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_id' => function () {
                return Chat::factory()->create(['type' => 'ticket'])->id;
            },
            'status_id' => function () {
                return TicketStatus::inRandomOrder()->first()?->id ??
                       TicketStatus::factory()->create()->id;
            },
            'priority_id' => function () {
                return TicketPriority::inRandomOrder()->first()?->id ??
                       TicketPriority::factory()->create()->id;
            },
            'assigned_to' => function () {
                return Admin::inRandomOrder()->first()?->id;
            },
            'last_response_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'closed_at' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween('-10 days', 'now') : null,
            'subject' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the ticket is closed.
     */
    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'closed_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
            ];
        });
    }

    /**
     * Indicate that the ticket is open.
     */
    public function open()
    {
        return $this->state(function (array $attributes) {
            return [
                'closed_at' => null,
            ];
        });
    }
}
