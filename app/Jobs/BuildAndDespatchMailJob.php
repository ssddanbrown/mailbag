<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\SendRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BuildAndDespatchMailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected SendRecord $sendRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SendRecord $sendRecord)
    {
        $this->sendRecord = $sendRecord;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send(new SendMail($this->sendRecord));
        $this->sendRecord->sent_at = now();
        $this->sendRecord->save();
    }
}
