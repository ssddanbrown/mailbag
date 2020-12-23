<div class="flex align-middle -mx-5">
    <div class="flex-auto px-5">
        <x-search-input/>
    </div>
    <div class="flex-auto px-5 text-right">
        <x-button-secondary-link :href="route('sends.create', ['campaign' => $campaign->id])">Create New</x-button-secondary-link>
    </div>
</div>

<div class="pb-5">{{ $sends->links() }}</div>

<x-table :headers="['Name', 'Status', '', '']">
    @foreach($sends as $send)
        <tr>
            <td class="table-cell">{{ $send->name }}</td>
            <td class="table-cell">
                <x-status-pill :color="$send->activated ? 'green' : 'blue'">
                    {{ $send->activated ? 'Launched' : 'Draft' }}
                </x-status-pill>
            </td>
            <td class="table-cell text-sm text-gray-800">
                @if($send->activated)
                    <span title="{{ $send->activated_at }}">Sent {{ $send->activated_at->diffForHumans() }}</span>
                @endif
            </td>
            <td class="table-cell text-sm font-medium text-right">
                <a href="{{ route('sends.show', ['send' => $send]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                @if(!$send->activated)
                    <span class="text-gray-400 px-2">|</span>
                    <a href="{{ route('sends.edit', ['send' => $send]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                @endif
            </td>
        </tr>
    @endforeach
</x-table>

<div class="py-5">{{ $sends->links() }}</div>
