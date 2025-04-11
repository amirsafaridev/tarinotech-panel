<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;

class TicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TicketStatusSeeder::class,
            TicketPrioritySeeder::class,
            TicketStatusTransitionSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
