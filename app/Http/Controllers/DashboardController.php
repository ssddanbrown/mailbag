<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\MailList;
use App\Models\RssFeed;
use App\Models\Send;
use App\Models\SendRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'recentSends'      => $this->recentSends(),
            'latestContacts'   => $this->latestContacts(),
            'popularLists'     => $this->popularLists(),
            'upcomingRssFeeds' => $this->upcomingRssFeeds(),
            'counts'           => $this->getCounts(),
        ]);
    }

    /**
     * @return array<string, int>
     */
    protected function getCounts(): array
    {
        return [
            'contacts' => Contact::query()->count(),
            'sends'    => Send::query()->count(),
            'sent'     => SendRecord::query()->count(),
        ];
    }

    protected function upcomingRssFeeds(): Collection
    {
        return RssFeed::query()
            ->with(['campaign', 'templateSend'])
            ->orderBy('next_review_at')
            ->take(5)
            ->get();
    }

    protected function popularLists(): Collection
    {
        return MailList::query()
            ->withCount('contacts')
            ->orderBy('contacts_count', 'desc')
            ->take(5)
            ->get();
    }

    protected function latestContacts(): Collection
    {
        return Contact::query()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->withCount(['lists'])
            ->get();
    }

    protected function recentSends(): Collection
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
