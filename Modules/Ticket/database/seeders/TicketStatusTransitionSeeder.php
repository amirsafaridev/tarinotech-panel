<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusTransition;

class TicketStatusTransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get status IDs (creating them if they don't exist)
        $waitingForReview = TicketStatus::where('name', 'در انتظار بررسی')->first()?->id;
        $inProgress = TicketStatus::where('name', 'در حال بررسی')->first()?->id;
        $waitingForCustomer = TicketStatus::where('name', 'منتظر پاسخ مشتری')->first()?->id;
        $waitingForSupport = TicketStatus::where('name', 'منتظر پاسخ پشتیبانی')->first()?->id;
        $resolved = TicketStatus::where('name', 'حل شده')->first()?->id;
        $closed = TicketStatus::where('name', 'بسته شده')->first()?->id;

        if (! $waitingForCustomer || ! $closed || ! $resolved || ! $waitingForSupport || ! $inProgress) {
            // Skip seeding if statuses don't exist yet
            return;
        }

        $transitions = [
            [
                'from_status_id' => $waitingForCustomer,
                'to_status_id' => $closed,
                'days_until_transition' => 3,
                'is_active' => true,
            ],
            [
                'from_status_id' => $resolved,
                'to_status_id' => $closed,
                'days_until_transition' => 5,
                'is_active' => true,
            ],
            [
                'from_status_id' => $waitingForSupport,
                'to_status_id' => $inProgress,
                'days_until_transition' => 0, // Immediate
                'is_active' => true,
            ],
        ];

        foreach ($transitions as $transition) {
            TicketStatusTransition::updateOrCreate(
                [
                    'from_status_id' => $transition['from_status_id'],
                    'to_status_id' => $transition['to_status_id'],
                ],
                $transition
            );
        }
    }
}
