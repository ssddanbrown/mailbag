<?php

namespace App\Console;

use App\Jobs\ClearExpiredSignupsJob;
use App\Jobs\DeleteUnsubscribedContactsJob;
use App\Jobs\FindRssFeedsToReviewJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new FindRssFeedsToReviewJob)->everyFifteenMinutes();
        $schedule->job(new ClearExpiredSignupsJob)->daily();
        $schedule->job(new DeleteUnsubscribedContactsJob)->daily();
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
