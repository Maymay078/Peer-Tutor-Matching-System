<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ListUsersWithDetails::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('booking:update-statuses')->daily();
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
