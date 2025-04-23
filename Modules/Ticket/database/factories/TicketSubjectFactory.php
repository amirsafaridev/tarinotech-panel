<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketSubject;

class TicketSubjectFactory extends Factory
{
    protected $model = TicketSubject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'is_published' => $this->faker->boolean(80), // 80% chance of being published
        ];
    }
}
