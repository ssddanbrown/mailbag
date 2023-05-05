<?php

namespace App\Console\Commands;

use App\Jobs\ScrubSignupsJob;
use Illuminate\Console\Command;

class ScrubSignupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:scrub-signups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the database of old expired sign-ups';

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
        dispatch_sync(new ScrubSignupsJob());

        return 0;
    }
}
