<?php

namespace Modules\Admin\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\app\Models\PersonnelReport;
use Modules\Admin\app\Models\Admin;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        // دریافت ادمین‌های موجود
        $admins = Admin::all();
        
        if ($admins->isEmpty()) {
            $this->command->error('No admins found. Please run AdminSeeder first.');
            return;
        }

        // ایجاد مرخصی‌های نمونه برای هفته جاری
        $currentDate = Verta::now()->startWeek();
        
        $sampleLeaves = [
            [
                'user_id' => $admins->random()->id,
                'start_date' => $currentDate->toCarbon(),
                'end_date' => $currentDate->toCarbon(),
                'type' => 'daily',
                'status' => 'approved',
                'description' => 'مرخصی روزانه'
            ],
            [
                'user_id' => $admins->random()->id,
                'start_date' => $currentDate->copy()->addDay()->toCarbon(),
                'end_date' => $currentDate->copy()->addDay()->toCarbon(),
                'type' => 'daily',
                'status' => 'approved',
                'description' => 'مرخصی روزانه'
            ],
            [
                'user_id' => $admins->random()->id,
                'start_date' => $currentDate->copy()->addDays(2)->toCarbon(),
                'end_date' => $currentDate->copy()->addDays(2)->toCarbon(),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'type' => 'hourly',
                'status' => 'approved',
                'description' => 'مرخصی ساعتی'
            ],
            [
                'user_id' => $admins->random()->id,
                'start_date' => $currentDate->copy()->addDays(3)->toCarbon(),
                'end_date' => $currentDate->copy()->addDays(3)->toCarbon(),
                'type' => 'daily',
                'status' => 'approved',
                'description' => 'مرخصی روزانه'
            ]
        ];

        foreach ($sampleLeaves as $leave) {
            PersonnelReport::create($leave);
        }

        $this->command->info('Sample leave requests created successfully.');
    }
} 