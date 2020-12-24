<x-app-layout>

    <x-heading title="Campaigns" :breadcrumbs="['Campaigns' => route('campaigns.index')]"/>

    <div class="container mx-auto">

        <div class="flex align-middle -mx-5">
            <div class="flex-auto px-5">
                <x-search-input/>
            </div>
            <div class="flex-auto px-5 text-right">
                <x-button-secondary-link :href="route('campaigns.create')">Create New</x-button-secondary-link>
            </div>
        </div>

        <div class="pb-5">{{ $campaigns->links() }}</div>

        <x-table :headers="['Name', '', '']">
            @foreach($campaigns as $campaign)
                <tr>
                    <td class="table-cell">
                        <a href="{{ route('campaigns.show', compact('campaign')) }}">{{ $campaign->name }}</a>
                    </td>
                    <td class="table-cell text-right">
                        @if($campaign->rss_feeds_count > 0)
                            <x-status-pill color="yellow" class="ml-2">
                                RSS
                            </x-status-pill>
                        @endif
                        <x-status-pill color="blue">
                            {{ $campaign->sends_count }}
                            {{ \Illuminate\Support\Str::plural('sends', $campaign->sends_count) }}
                        </x-status-pill>
                    </td>
                    <td class="table-cell text-sm text-right font-medium">
                        <a href="{{ route('campaigns.show', ['campaign' => $campaign]) }}" class="link">View</a>
                        <span class="text-gray-400 px-2">|</span>
                        <a href="{{ route('campaigns.edit', ['campaign' => $campaign]) }}" class="link">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $campaigns->links() }}</div>
    </div>

</x-app-layout>
