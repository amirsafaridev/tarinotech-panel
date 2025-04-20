<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\database\factories\TicketTransitionFactory;

class TicketTransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketTransitionFactory::new()
            ->count(5)
            ->create();
    }
}
