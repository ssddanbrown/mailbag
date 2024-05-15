<?php

use App\Jobs\FindRssFeedsToReviewJob;
use App\Jobs\ScrubSignupsJob;
use App\Jobs\ScrubUnsubscribesJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new FindRssFeedsToReviewJob())->everyFifteenMinutes();
Schedule::job(new ScrubSignupsJob())->daily();
Schedule::job(new ScrubUnsubscribesJob())->daily();
