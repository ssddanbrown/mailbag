<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Send;
use App\Models\SendRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendActivationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $send;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Send $send)
    {
        $this->send = $send;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $listMembers = $this->send->maillist->contacts()
            ->where('unsubscribed', '=', false)
            ->get(['id']);

        $sendKey = Str::random(16);
        $recordKeys = SendRecord::generateNewKeys($listMembers->count());
        $sendRecords = $listMembers->map(function($member) use ($sendKey, $recordKeys) {
            return new SendRecord([
                'contact_id' => $member->id,
                'key' => $sendKey . '-' . $recordKeys->pop(),
            ]);
        });

        $this->send->records()->saveMany($sendRecords);

        $sendRecords->each(function(SendRecord $sendRecord) {
            dispatch(new BuildAndDespatchMailJob($sendRecord));
        });
    }
}
