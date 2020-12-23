<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Campaign::query()->orderBy('name');
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
    public function show(Request $request, Campaign $campaign)
    {
        $sendQuery = $campaign->sends()->orderByRaw('activated_at desc NULLS FIRST')->orderBy('name');
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
    public function create()
    {
        $default = new Campaign();
        return view('campaigns.create', ['campaign' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:250',
        ]);

        $campaign = new Campaign([
            'name' => $request->get('name'),
        ]);
        $campaign->save();

        $this->showSuccessMessage('Campaign created!');
        return redirect()->route('campaigns.edit', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->validate($request, [
            'name' => 'required|max:250',
        ]);

        $campaign->update([
            'name' => $request->get('name'),
        ]);

        $this->showSuccessMessage('Campaign updated!');
        return redirect()->route('campaigns.edit', compact('campaign'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        $campaign->sends()->delete();
        // TODO - Handle sub-send relations
        $this->showSuccessMessage("Campaign deleted!");
        return redirect()->route('campaigns.index');
    }
}
