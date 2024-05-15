<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MailList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Contact::query()->orderBy('email')->withCount(['lists']);
        $search = $request->get('search');
        if ($search) {
            $query->where('email', 'like', '%' . $search . '%');
        }

        $contacts = $query->paginate(100)->withQueryString();

        return view('contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $default = new Contact(['unsubscribed' => false]);

        return view('contacts.create', ['contact' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email'        => 'required|email|unique:contacts,email',
            'unsubscribed' => 'boolean',
        ]);

        $contact = new Contact([
            'email'        => $request->get('email'),
            'unsubscribed' => $request->get('unsubscribed') === '1',
        ]);
        $contact->save();

        $this->showSuccessMessage('Contact created!');

        return redirect()->route('contacts.edit', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): View
    {
        $allListOptions = MailList::query()->whereNotIn('id', $contact->lists()->pluck('id'))
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($list) {
                return [$list->id => $list->name];
            })->toArray();

        return view('contacts.edit', [
            'contact'     => $contact,
            'listOptions' => $allListOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $this->validate($request, [
            'email'        => "required|email|unique:contacts,email,{$contact->id}",
            'unsubscribed' => 'boolean',
        ]);

        $contact->update([
            'email'        => $request->get('email'),
            'unsubscribed' => $request->get('unsubscribed') === '1',
        ]);

        $this->showSuccessMessage('Contact updated!');

        return redirect()->route('contacts.edit', compact('contact'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->deleteWithRelations();
        $this->showSuccessMessage('Contact deleted!');

        return redirect()->route('contacts.index');
    }
}
