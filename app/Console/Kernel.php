<?php

namespace App\Console;

use App\Appointment;
use App\Contact;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Runs only when the server's cron calls "php artisan schedule:run" every
     * minute (DEPLOYMENT.md, "Scheduled tasks").
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Daily database dump, newest 14 kept (deploys keep their own, see scripts/deploy.sh).
        $schedule->command('db:backup', ['--prefix' => 'just-lite-daily', '--keep' => 14])
            ->dailyAt('02:30')
            ->withoutOverlapping();

        // Delete contact messages and bookings older than the privacy policy's
        // retention period (config site.retention_months).
        $schedule->command('model:prune', ['--model' => [Contact::class, Appointment::class]])
            ->dailyAt('03:00')
            ->withoutOverlapping();
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
