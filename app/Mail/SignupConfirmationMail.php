<?php

namespace App\Mail;

use App\Models\Signup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $signup;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Signup $signup)
    {
        $this->signup = $signup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Confirm your subscription to {$this->signup->maillist->display_name}")
        ->text('signups.confirmation-email', [
            'signup' => $this->signup,
        ]);
    }
}
