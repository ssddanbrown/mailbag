<?php

namespace App\Console\Commands;

use App\Jobs\ClearExpiredSignupsJob;
use Illuminate\Console\Command;

class ClearExpiredSignupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:clear-expired-signups';

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
     *
     * @return int
     */
    public function handle()
    {
        dispatch_now(new ClearExpiredSignupsJob());
        return 0;
    }
}
