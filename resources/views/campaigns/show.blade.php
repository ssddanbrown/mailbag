<x-app-layout>
    <x-heading title="Campaign" :subtitle="$campaign->name" :breadcrumbs="['Campaigns' => route('campaigns.index'), $campaign->name => route('campaigns.show', compact('campaign'))]"/>

    <div class="container">

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto px-2">
                <x-button-secondary-link :href="route('rss.create', compact('campaign'))">Setup RSS Feed</x-button-secondary-link>
            </div>
            <div class="flex-auto px-2 text-right">
                <x-button-secondary-link :href="route('campaigns.edit', compact('campaign'))">Edit Campaign</x-button-secondary-link>
            </div>
        </div>

        @if($campaign->rssFeeds->count() > 0)
            <div class="border-t border-gray-300 pb-5">
                <x-subheading>RSS Feeds</x-subheading>
                @include('campaigns.rss-feed-list')
            </div>
        @endif

        <div class="border-t border-gray-300">
            <x-subheading>Sends</x-subheading>
            @include('campaigns.send-list')
        </div>
    </div>

</x-app-layout>
