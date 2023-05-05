<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Campaign::query()->withCount(['sends', 'rssFeeds'])->orderBy('name');
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $campaigns = $query->paginate(100)->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Display this particular campaign.
     */
    public function show(Request $request, Campaign $campaign): View
    {
        $sendQuery = $campaign->sends()
            ->orderByRaw('activated_at desc NULLS FIRST')
            ->orderBy('name')
            ->with(['mailList'])
            ->withCount(['feeds']);
        $search = $request->get('search');
        if ($search) {
            $sendQuery->where('name', 'like', '%' . $search . '%');
        }

        $sends = $sendQuery->paginate(100)->withQueryString();

        return view('campaigns.show', compact('campaign', 'sends'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $default = new Campaign();

        return view('campaigns.create', ['campaign' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|max:250',
        ]);

        $campaign = new Campaign([
            'name' => $request->get('name'),
        ]);
        $campaign->save();

        $this->showSuccessMessage('Campaign created!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign): View
    {
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|max:250',
        ]);

        $campaign->update([
            'name' => $request->get('name'),
        ]);

        $this->showSuccessMessage('Campaign updated!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();
        $campaign->sends()->delete();
        $campaign->rssFeeds()->delete();
        $this->showSuccessMessage('Campaign deleted!');

        return redirect()->route('campaigns.index');
    }
}
