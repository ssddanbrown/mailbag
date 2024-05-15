<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Send;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendController extends Controller
{
    /**
     * Display this particular send.
     */
    public function show(Send $send): View
    {
        return view('sends.show', compact('send'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $default = new Send();

        if ($request->has('copy_from')) {
            $default->fill(Send::query()->findOrNew($request->get('copy_from'))->getAttributes());
        }

        $campaign = null;
        if ($request->has('campaign')) {
            $campaign = Campaign::query()->find($request->get('campaign'));
        }

        return view('sends.create', [
            'send'     => $default,
            'campaign' => $campaign,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request, [
            'name'         => 'required|max:250',
            'content'      => 'required|max:25000',
            'subject'      => 'required|max:250',
            'mail_list_id' => 'required|exists:mail_lists,id',
            'campaign_id'  => 'required|exists:campaigns,id',
        ]);

        $send = new Send($validated);
        $send->save();

        $this->showSuccessMessage('Send created!');

        return redirect()->route('sends.show', compact('send'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Send $send): View
    {
        return view('sends.edit', compact('send'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Send $send): RedirectResponse
    {
        $validated = $this->validate($request, [
            'name'         => 'required|max:250',
            'content'      => 'required|max:25000',
            'subject'      => 'required|max:250',
            'mail_list_id' => 'exists:mail_lists,id',
            'campaign_id'  => 'exists:campaigns,id',
        ]);

        $send->update($validated);

        $this->showSuccessMessage('Send updated!');

        return redirect()->route('sends.show', compact('send'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Send $send): RedirectResponse
    {
        $campaign = $send->campaign;
        $send->delete();
        $this->showSuccessMessage('Send deleted!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }
}
