<x-app-layout>
    <x-heading title="Edit RSS Feed" :subtitle="$feed->url" :breadcrumbs="['Campaigns' => route('campaigns.index'), $campaign->name => route('campaigns.show', compact('campaign')), 'Edit RSS Feed' => route('rss.edit', compact('campaign', 'feed'))]"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('rss.update', ['campaign' => $feed->campaign, 'feed' => $feed]) }}" method="post" id="rss-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('rss.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('campaigns.show', ['campaign' => $feed->campaign]) }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('rss.destroy', ['campaign' => $feed->campaign, 'feed' => $feed])">
                Are you sure you want to delete this RSS Feed?
            </x-delete-dropdown>
            <x-button form="rss-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
