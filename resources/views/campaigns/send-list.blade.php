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
    @if (count($sends) === 0)
        <tr>
            <td colspan="4" class="table-cell italic text-gray-600">No sends found!</td>
        </tr>
    @endif
    @foreach($sends as $send)
        <tr>
            <td class="table-cell">
                <a href="{{ route('sends.show', compact('send')) }}">{{ $send->name }}</a> <br>
                <a href="{{ route('lists.show', ['list' => $send->mailList]) }}" class="text-sm text-gray-600 hover:underline">List: {{ $send->mailList->name }}</a>
            </td>
            <td class="table-cell">
                <x-status-pill :color="$send->activated ? 'green' : 'blue'">
                    {{ $send->activated ? 'Launched' : 'Draft' }}
                </x-status-pill>
                @if ($send->feeds_count > 0)
                    <br>
                    <x-status-pill color="yellow" class="mt-1">
                        Used in RSS
                    </x-status-pill>
                @endif
            </td>
            <td class="table-cell text-sm text-gray-800">
                @if($send->activated)
                    <span title="{{ $send->activated_at }}">Sent {{ $send->activated_at->diffForHumans() }}</span>
                @endif
            </td>
            <td class="table-cell text-sm font-medium text-right">
                <a href="{{ route('sends.show', ['send' => $send]) }}" class="link">View</a>
                @if(!$send->activated)
                    <span class="text-gray-400 px-2">|</span>
                    <a href="{{ route('sends.edit', ['send' => $send]) }}" class="link">Edit</a>
                @endif
            </td>
        </tr>
    @endforeach
</x-table>

<div class="py-5">{{ $sends->links() }}</div>
