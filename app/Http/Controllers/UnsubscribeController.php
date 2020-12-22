<?php

namespace App\Http\Controllers;

use App\Models\SendRecord;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    /**
     * Show the view with the unsubscribe options.
     */
    public function show($sendRecord)
    {
        $sendRecord = SendRecord::query()->where('key', '=', $sendRecord)->first();
        if (!$sendRecord || ($sendRecord && $sendRecord->hasExpired())) {
            return response()->view('unsubscribe.not-found', [], 404);
        }

        return view('unsubscribe.show', compact('sendRecord'));
    }

    /**
     * Unsubscribe from the list.
     */
    public function fromList(SendRecord $sendRecord)
    {
        $list = $sendRecord->send->maillist;
        $contact = $sendRecord->contact;
        $contact->lists()->detach([$list->id]);
        return redirect()->route('unsubscribe.confirm', ['type' => 'list']);
    }

    /**
     * Unsubscribe at a contact level.
     */
    public function fromAll(SendRecord $sendRecord)
    {
        $contact = $sendRecord->contact;
        $contact->markUnsubscribed();
        return redirect()->route('unsubscribe.confirm', ['type' => 'all']);
    }

    /**
     * Show the confirm (ty) page for unsubscribing.
     */
    public function confirm(Request $request)
    {
        return view('unsubscribe.confirm', [
            'type' => $request->get('type', 'all'),
        ]);
    }
}
