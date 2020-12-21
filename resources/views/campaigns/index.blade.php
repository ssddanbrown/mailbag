<x-app-layout>

    <x-heading title="Campaigns"/>

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

        <x-table :headers="['Name', '']">
            @foreach($campaigns as $campaign)
                <tr>
                    <td class="table-cell">{{ $campaign->name }}</td>
                    <td class="table-cell text-sm font-medium">
                        <a href="{{ route('campaigns.edit', ['campaign' => $campaign]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $campaigns->links() }}</div>
    </div>

</x-app-layout>
