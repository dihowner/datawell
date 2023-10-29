<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\AirtimeDispatcher::class,
    ];
     
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('airtime:dispatcher')->everyMinute();
        $schedule->command('mtndata:dispatcher')->everyMinute();
        $schedule->command('airteldata:dispatcher')->everyMinute();
        $schedule->command('glodata:dispatcher')->everyMinute();
        $schedule->command('etisalatdata:dispatcher')->everyMinute();
        $schedule->command('verify:airtime')->everyMinute();
        $schedule->command('verify:data')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}