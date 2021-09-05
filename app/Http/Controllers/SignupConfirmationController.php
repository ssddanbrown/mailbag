<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MailList;
use App\Models\Signup;
use Illuminate\Http\Request;

class SignupConfirmationController extends Controller
{
    /**
     * Show the page for confirming a subscription.
     */
    public function show(string $signupKey)
    {
        /** @var ?Signup $signup */
        $signup = Signup::query()->where('key', '=', $signupKey)->first();

        if ($signup && $signup->hasExpired()) {
            $signup->delete();
            $signup = null;
        }

        if (is_null($signup)) {
            return response()->view('signups.confirm-not-found', [], 404);
        }

        return view('signups.confirm', compact('signup'));
    }

    /**
     * The request to confirm the subscription.
     */
    public function confirm(Signup $signup)
    {
        $contact = Contact::query()->updateOrCreate(['email' => $signup->email], ['unsubscribed' => false]);

        $signup->maillist->contacts()->attach($contact->id);
        $list = $signup->maillist;
        $signup->delete();

        return redirect()->route('signup.confirm.thanks', compact('list'));
    }

    /**
     * Show the thank-you page for a sign-up.
     */
    public function thanks(MailList $list)
    {
        return view('signups.confirm-thanks', compact('list'));
    }
}
