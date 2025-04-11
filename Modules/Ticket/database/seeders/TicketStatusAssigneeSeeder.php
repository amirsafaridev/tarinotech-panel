<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusAssignee;
use Spatie\Permission\Models\Role;

class TicketStatusAssigneeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get statuses
        $waitingForReview = TicketStatus::where('name', 'در انتظار بررسی')->first();
        $inProgress = TicketStatus::where('name', 'در حال بررسی')->first();
        $waitingForCustomer = TicketStatus::where('name', 'منتظر پاسخ مشتری')->first();
        $waitingForSupport = TicketStatus::where('name', 'منتظر پاسخ پشتیبانی')->first();
        $escalated = TicketStatus::where('name', 'ارجاع شده')->first();
        $resolved = TicketStatus::where('name', 'حل شده')->first();

        // Get roles
        $supportRole = Role::where('name', 'پشتیبان وب سایت')->where('guard_name', 'admin')->first();
        $managerRole = Role::where('name', 'مدیر پروژه')->where('guard_name', 'admin')->first();
        $designerRole = Role::where('name', 'کارشناس طراحی')->where('guard_name', 'admin')->first();

        // Skip if required data is missing
        if (! $supportRole || ! $managerRole || ! $waitingForReview) {
            return;
        }

        $assignments = [
            [
                'status' => $waitingForReview,
                'role' => $supportRole,
            ],
            [
                'status' => $inProgress,
                'role' => $supportRole,
            ],
            [
                'status' => $waitingForCustomer,
                'role' => $supportRole,
            ],
            [
                'status' => $waitingForSupport,
                'role' => $supportRole,
            ],
            [
                'status' => $escalated,
                'role' => $managerRole,
            ],
            [
                'status' => $resolved,
                'role' => $supportRole,
            ],
        ];

        foreach ($assignments as $assignment) {
            if ($assignment['status'] && $assignment['role']) {
                TicketStatusAssignee::updateOrCreate(
                    ['status_id' => $assignment['status']->id],
                    ['role_id' => $assignment['role']->id]
                );
            }
        }
    }
}
