<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\MakeService::class,
        \App\Console\Commands\BackupTicketsCsv::class,
        \App\Http\Middleware\SetLocale::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('network:monitor')->everyMinute();
        $schedule->command('incident:scan')->everyMinute();
        $schedule->command('tickets:backup')->dailyAt('00:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}