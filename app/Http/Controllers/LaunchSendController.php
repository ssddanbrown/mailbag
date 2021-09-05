<?php

namespace App\Http\Controllers;

use App\Jobs\SendActivationJob;
use App\Models\Send;

class LaunchSendController extends Controller
{
    /**
     * Launch the email send.
     */
    public function launch(Send $send)
    {
        if ($send->activated) {
            $this->showErrorMessage('This send has already been activated!');

            return redirect()->route('sends.show', compact('send'));
        }

        $send->activated_at = now();
        $send->save();
        $this->dispatch(new SendActivationJob($send));

        $this->showSuccessMessage('Send activated and queued for mailing!');

        return redirect()->route('sends.show', compact('send'));
    }
}
