<?php

namespace App\Console\Commands;

use App\Jobs\DeleteUnsubscribedContactsJob;
use Illuminate\Console\Command;

class DeleteUnsubscribedContactsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailbag:delete-unsubscribed-contacts';

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
     *
     * @return int
     */
    public function handle()
    {
        dispatch_now(new DeleteUnsubscribedContactsJob);
        return 0;
    }
}
