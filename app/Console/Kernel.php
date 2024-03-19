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
        $schedule->command('app:update-user-data')->everyFiveMinutes(); //daily 12:18pm 
        $schedule->command('contract-expiring-notification')->everyFiveMinutes();
        $schedule->command('app:contract-status-update')->everyFiveMinutes();
        $schedule->command('app:msa-expiring-check')->everyFiveMinutes();
    }
    protected $commands = [
        // Other commands...
        \App\Console\Commands\ContractExpiringNotification::class,
        \App\Console\Commands\ContractStatusUpdate::class,
        \App\Console\Commands\MsaExpiringCheck::class,
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
