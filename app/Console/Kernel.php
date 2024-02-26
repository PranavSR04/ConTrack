<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:update-user-data')->dailyAt('7:07'); //daily 12:20pm 
        $schedule->command('contract-expiring-notification')->dailyAt('7:07');
        $schedule->command('app:contract-status-update')->dailyAt('7:07');
    }
    protected $commands = [
        // Other commands...
        \App\Console\Commands\ContractExpiringNotification::class,
        \App\Console\Commands\ContractStatusUpdate::class,
    ];
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
