<?php   namespace App\Console;

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
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('usersubscription:expire_refill')->daily();
        $schedule->command('send:every-night-notification')->daily();
        $schedule->command('scan:limit')->dailyAt('12:00');
        $schedule->command('remove:preappointment')->everyThirtyMinutes();
        $schedule->command('instant:off')->dailyAt('16:00', '20:00');
        $schedule->command('callstart:cron')->everyMinute();
        $schedule->command('overappointments:cron')->everyMinute();
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
