<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MailList;
use App\Models\RssFeed;
use App\Models\Send;
use App\Models\SendRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'recentSends' => $this->recentSends(),
            'latestContacts' => $this->latestContacts(),
            'popularLists' => $this->popularLists(),
            'upcomingRssFeeds' => $this->upcomingRssFeeds(),
            'counts' => $this->getCounts(),
        ]);
    }

    protected function getCounts()
    {
        return [
            'contacts' => Contact::query()->count(),
            'sends' => Send::query()->count(),
            'sent' => SendRecord::query()->count(),
        ];
    }

    protected function upcomingRssFeeds()
    {
        return RssFeed::query()
            ->with(['campaign', 'templateSend'])
            ->orderBy('next_review_at')
            ->take(5)
            ->get();
    }

    protected function popularLists()
    {
        return MailList::query()
            ->withCount('contacts')
            ->orderBy('contacts_count', 'desc')
            ->take(5)
            ->get();
    }

    protected function latestContacts()
    {
        return Contact::query()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->withCount(['lists'])
            ->get();
    }

    protected function recentSends()
    {
        return Send::query()
            ->whereNotNull('activated_at')
            ->orderBy('activated_at', 'desc')
            ->take(10)
            ->with(['campaign'])
            ->withCount(['records'])
            ->get();
    }
}
