<x-app-layout>
    <x-heading title="RSS Feed" subtitle="Create new RSS Feed" :breadcrumbs="['Campaigns' => route('campaigns.index'), $campaign->name => route('campaigns.show', compact('campaign')), 'New RSS Feed' => route('rss.create', compact('campaign'))]"/>

    <div class="container">

        <form action="{{ route('rss.store', compact('campaign')) }}" method="post" id="rss-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('rss.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('campaigns.show', ['campaign' => $feed->campaign]) }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-button form="rss-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
