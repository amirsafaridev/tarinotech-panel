<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Models\TicketPriority;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            [
                'name' => 'بحرانی',
                'color' => '#F44336', // red
                'description' => 'مشکلات بسیار حاد که نیاز به رسیدگی فوری دارند',
                'should_notify' => true,
                'level' => 1,
            ],
            [
                'name' => 'بالا',
                'color' => '#FF9800', // orange
                'description' => 'مشکلات مهم با اولویت بالا',
                'should_notify' => false,
                'level' => 2,
            ],
            [
                'name' => 'متوسط',
                'color' => '#FFC107', // yellow
                'description' => 'مشکلات با اولویت متوسط',
                'should_notify' => false,
                'level' => 3,
            ],
            [
                'name' => 'پایین',
                'color' => '#2196F3', // blue
                'description' => 'مشکلات با اولویت پایین',
                'should_notify' => false,
                'level' => 4,
            ],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::create($priority);
        }
    }
}
