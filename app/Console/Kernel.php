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
     * The Artisan commands provided by your application.
     *
     * @var string[]
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new FindRssFeedsToReviewJob())->everyFifteenMinutes();
        $schedule->job(new ScrubSignupsJob())->daily();
        $schedule->job(new ScrubUnsubscribesJob())->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
