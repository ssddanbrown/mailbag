<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MailList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactListController extends Controller
{
    /**
     * Add the lists of the given ids to the given contact.
     */
    public function add(Request $request, Contact $contact): RedirectResponse
    {
        $this->validate($request, [
            'lists'   => 'array',
            'lists.*' => 'integer',
        ]);

        $listIds = MailList::query()->whereIn('id', $request->get('lists'))->pluck('id');
        $contact->lists()->syncWithoutDetaching($listIds);

        $this->showSuccessMessage("Added contact to {$listIds->count()} lists");

        return redirect()->route('contacts.edit', compact('contact'));
    }

    /**
     * Remove the lists of the given ids from the given contact.
     */
    public function remove(Request $request, Contact $contact): RedirectResponse
    {
        $this->validate($request, [
            'lists'   => 'array',
            'lists.*' => 'integer',
        ]);

        $listIds = MailList::query()->whereIn('id', $request->get('lists'))->pluck('id');
        $contact->lists()->detach($listIds);

        $this->showSuccessMessage("Removed contact from {$listIds->count()} lists");

        return redirect()->route('contacts.edit', compact('contact'));
    }
}
