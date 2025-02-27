<?php

namespace App\Console;

use App\Console\Commands\DashboardSummaryReport;
use App\Console\Commands\FileRemainder;
use App\Console\Commands\SummaryReport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
         $schedule->command(FileRemainder::class)->everyMinute();
         $schedule->command(SummaryReport::class)->everyMinute();
         $schedule->command(DashboardSummaryReport::class)->everyMinute();
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
