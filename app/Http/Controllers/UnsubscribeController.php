<?php

namespace App\Http\Controllers;

use App\Models\SendRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnsubscribeController extends Controller
{
    /**
     * Show the view with the unsubscribe options.
     *
     * @return View|Response
     */
    public function show(string $sendRecordKey)
    {
        /** @var ?SendRecord $sendRecord */
        $sendRecord = SendRecord::query()->where('key', '=', $sendRecordKey)->first();
        if (!$sendRecord || $sendRecord->hasExpired()) {
            return response()->view('unsubscribe.not-found', [], 404);
        }

        return view('unsubscribe.show', compact('sendRecord'));
    }

    /**
     * Unsubscribe from the list.
     */
    public function fromList(SendRecord $sendRecord): RedirectResponse
    {
        $list = $sendRecord->send->maillist;
        $contact = $sendRecord->contact;
        $contact->lists()->detach([$list->id]);

        return redirect()->route('unsubscribe.confirm', ['type' => 'list']);
    }

    /**
     * Unsubscribe at a contact level.
     */
    public function fromAll(SendRecord $sendRecord): RedirectResponse
    {
        $contact = $sendRecord->contact;
        $contact->markUnsubscribed();

        return redirect()->route('unsubscribe.confirm', ['type' => 'all']);
    }

    /**
     * Show the confirm (ty) page for unsubscribing.
     */
    public function confirm(Request $request): View
    {
        return view('unsubscribe.confirm', [
            'type' => $request->get('type', 'all'),
        ]);
    }
}
