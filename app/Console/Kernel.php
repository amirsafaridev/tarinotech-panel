<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Hekmatinasser\Verta\Verta;
use Modules\Personnel\App\Jobs\GeneratePayslipsJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $today = Verta::now()->day; // دریافت روز جاری شمسی
    
            if ($today == 1) { // اگر روز اول ماه شمسی بود
                GeneratePayslipsJob::dispatch();
            }
        })->dailyAt('00:05'); // اجرا در ساعت ۰۰:۰۵ هر روز
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
