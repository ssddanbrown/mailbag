<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\RssFeed;
use App\Rules\ValidRssFeedRule;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    /**
     * Show the form to create a new RSS feed setup.
     */
    public function create(Campaign $campaign)
    {
        $feed = new RssFeed();
        $feed->campaign = $campaign;
        return view('rss.create', compact('campaign', 'feed'));
    }

    /**
     * Store this RSS feed config in the database.
     */
    public function store(Request $request, Campaign $campaign)
    {
        $validated = $this->validate($request, [
            'active' => 'required|bool',
            'url' => ['required', 'url', 'max:250', new ValidRssFeedRule],
            'template_send_id' => 'required|exists:sends,id',
            'send_frequency' => 'required|integer|min:1|max:366',
        ]);

        $feed = new RssFeed($validated);
        $campaign->rssFeeds()->save($feed);

        $this->showSuccessMessage("RSS feed created!");
        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Edit this RSS feed.
     */
    public function edit(Campaign $campaign, RssFeed $feed)
    {
        return view('rss.edit', compact('campaign', 'feed'));
    }

    /**
     * Update the details of this RSS feed.
     */
    public function update(Request $request, Campaign $campaign, RssFeed $feed)
    {
        $validated = $this->validate($request, [
            'active' => 'required|bool',
            'url' => ['required', 'url', 'max:250', new ValidRssFeedRule],
            'template_send_id' => 'required|exists:sends,id',
            'send_frequency' => 'required|integer|min:1|max:366',
        ]);

        $feed->update($validated);

        $this->showSuccessMessage("RSS feed updated!");
        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Delete the RSS feed.
     */
    public function destroy(Campaign $campaign, RssFeed $feed)
    {
        $feed->delete();

        $this->showSuccessMessage("RSS feed deleted!");
        return redirect()->route('campaigns.show', compact('campaign'));
    }
}
