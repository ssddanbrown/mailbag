<?php

namespace App\Http\Controllers;

use App\Jobs\SendActivationJob;
use App\Models\Send;
use Illuminate\Http\RedirectResponse;

class LaunchSendController extends Controller
{
    /**
     * Launch the email send.
     */
    public function launch(Send $send): RedirectResponse
    {
        if ($send->activated) {
            $this->showErrorMessage('This send has already been activated!');

            return redirect()->route('sends.show', compact('send'));
        }

        $send->activated_at = now();
        $send->save();
        dispatch(new SendActivationJob($send));

        $this->showSuccessMessage('Send activated and queued for mailing!');

        return redirect()->route('sends.show', compact('send'));
    }
}
