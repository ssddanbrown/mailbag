<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Contact::query()->orderBy('email');
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
    public function create()
    {
        $default = new Contact(['unsubscribed' => false]);
        return view('contacts.create', ['contact' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:contacts,email',
            'unsubscribed' => 'boolean',
        ]);

        $contact = new Contact([
            'email' => $request->get('email'),
            'unsubscribed' => $request->get('unsubscribed') === '1',
        ]);
        $contact->save();

        $this->showSuccessMessage('Contact created!');
        return redirect()->route('contacts.edit', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', ['contact' => $contact]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $this->validate($request, [
            'email' => "required|email|unique:contacts,email,{$contact->id}",
            'unsubscribed' => 'boolean',
        ]);

        $contact->update([
            'email' => $request->get('email'),
            'unsubscribed' => $request->get('unsubscribed') === '1',
        ]);

        $this->showSuccessMessage('Contact updated!');
        return redirect()->route('contacts.edit', compact('contact'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        $this->showSuccessMessage("Contact deleted!");
        return redirect()->route('contacts.index');
    }
}
