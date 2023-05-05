<?php

namespace App\Console\Commands;

use App\Jobs\ScrubUnsubscribesJob;
use Illuminate\Console\Command;

class ScrubUnsubscribesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:scrub-unsubscribes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete any unsubscribed contacts from the database';

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
        dispatch_sync(new ScrubUnsubscribesJob());

        return 0;
    }
}
