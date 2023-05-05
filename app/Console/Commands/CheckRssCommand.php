<?php

namespace App\Console\Commands;

use App\Jobs\FindRssFeedsToReviewJob;
use Illuminate\Console\Command;

class CheckRssCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:check-rss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the configured RSS feeds and run any pending sends';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        dispatch_sync(new FindRssFeedsToReviewJob());

        return 0;
    }
}
