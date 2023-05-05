<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\RssFeed;
use App\Rules\ValidRssFeedRule;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    /**
     * Show the form to create a new RSS feed setup.
     */
    public function create(Campaign $campaign): View
    {
        $feed = new RssFeed();
        $feed->campaign = $campaign;

        return view('rss.create', compact('campaign', 'feed'));
    }

    /**
     * Store this RSS feed config in the database.
     */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $this->validate($request, [
            'active'           => 'required|bool',
            'url'              => ['required', 'url', 'max:250', new ValidRssFeedRule()],
            'template_send_id' => 'required|exists:sends,id',
            'send_frequency'   => 'required|integer|min:1|max:366',
            'target_hour'      => 'required|integer|min:0|max:23',
        ]);

        $feed = new RssFeed($validated);
        $feed->last_reviewed_at = CarbonImmutable::now();
        $feed->updateNextReviewDate();
        $campaign->rssFeeds()->save($feed);

        $this->showSuccessMessage('RSS feed created!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Edit this RSS feed.
     */
    public function edit(Campaign $campaign, RssFeed $feed): View
    {
        return view('rss.edit', compact('campaign', 'feed'));
    }

    /**
     * Update the details of this RSS feed.
     */
    public function update(Request $request, Campaign $campaign, RssFeed $feed): RedirectResponse
    {
        $validated = $this->validate($request, [
            'active'           => 'required|bool',
            'url'              => ['required', 'url', 'max:250', new ValidRssFeedRule()],
            'template_send_id' => 'required|exists:sends,id',
            'send_frequency'   => 'required|integer|min:1|max:366',
            'target_hour'      => 'required|integer|min:0|max:23',
        ]);

        $feed->fill($validated);
        $feed->updateNextReviewDate();
        $feed->update($validated);

        $this->showSuccessMessage('RSS feed updated!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }

    /**
     * Delete the RSS feed.
     */
    public function destroy(Campaign $campaign, RssFeed $feed): RedirectResponse
    {
        $feed->delete();

        $this->showSuccessMessage('RSS feed deleted!');

        return redirect()->route('campaigns.show', compact('campaign'));
    }
}
