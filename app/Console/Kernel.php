<?php

namespace App\Console;

use App\Jobs\FindRssFeedsToReviewJob;
use App\Jobs\ScrubSignupsJob;
use App\Jobs\ScrubUnsubscribesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new FindRssFeedsToReviewJob())->everyFifteenMinutes();
        $schedule->job(new ScrubSignupsJob())->daily();
        $schedule->job(new ScrubUnsubscribesJob())->daily();
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
