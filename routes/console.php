<?php

use App\Jobs\FindRssFeedsToReviewJob;
use App\Jobs\ScrubSignupsJob;
use App\Jobs\ScrubUnsubscribesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new FindRssFeedsToReviewJob())->everyFifteenMinutes();
Schedule::job(new ScrubSignupsJob())->daily();
Schedule::job(new ScrubUnsubscribesJob())->daily();
