<?php

namespace Modules\Ticket\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusAssignee;
use Spatie\Permission\Models\Role;

class TicketStatusAssigneeFactory extends Factory
{
    protected $model = TicketStatusAssignee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_id' => function () {
                return TicketStatus::inRandomOrder()->first()?->id ??
                       TicketStatus::factory()->create()->id;
            },
            'role_id' => function () {
                return Role::where('guard_name', 'admin')->inRandomOrder()->first()?->id;
            },
        ];
    }
}
