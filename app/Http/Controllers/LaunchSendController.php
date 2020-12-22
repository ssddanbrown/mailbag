<?php

namespace App\Http\Controllers;

use App\Jobs\SendActivationJob;
use App\Models\Send;
use Illuminate\Http\Request;

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

        $this->dispatch(new SendActivationJob($send));
        $send->activated_at = now();
        $send->save();

        $this->showSuccessMessage('Send activated and queued for mailing!');
        return redirect()->route('sends.show', compact('send'));
    }
}
