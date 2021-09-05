<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Mail\SignupConfirmationMail;
use App\Models\MailList;
use App\Models\Signup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class SignupController extends Controller
{
    /**
     * Show the view where a contact can sign-up to a mailing list.
     */
    public function show(MailList $list): View
    {
        return view('signups.show', compact('list'));
    }

    /**
     * Handle the request for a user wanting to sign up to a mailing list.
     */
    public function signup(SignupRequest $request, MailList $list): RedirectResponse
    {
        $email = $request->get('email');
        $keys = ['email' => $email, 'mail_list_id' => $list->id];

        $signup = Signup::query()->where($keys)->first();
        if (is_null($signup)) {
            $signup = (new Signup())->forceFill(array_merge($keys, [
                'attempts' => 0,
            ]));
        }
        $signup->attempts += 1;
        $signup->key = Signup::generateNewKey();
        $signup->save();

        Mail::to($email)->send(new SignupConfirmationMail($signup));

        return redirect()->route('signup.thanks', compact('list'));
    }

    /**
     * Show the thanks view after initial sign-up.
     */
    public function thanks(MailList $list): View
    {
        return view('signups.thanks', compact('list'));
    }
}
