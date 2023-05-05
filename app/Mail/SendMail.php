<?php

namespace App\Mail;

use App\Models\SendRecord;
use App\Services\MailContentParser;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use SerializesModels;

    protected SendRecord $sendRecord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SendRecord $sendRecord)
    {
        $this->sendRecord = $sendRecord;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $send = $this->sendRecord->send;
        $content = (new MailContentParser($send->content))->parseForSend($this->sendRecord);

        return $this->to($this->sendRecord->contact->email)
            ->subject($this->sendRecord->send->subject)
            ->text('mail.blank', ['content' => $content]);
    }
}
