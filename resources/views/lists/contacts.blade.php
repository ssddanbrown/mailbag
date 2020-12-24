<div class="flex align-middle -mx-5">
    <div class="flex-auto px-5">
        <x-search-input/>
    </div>
    <div class="flex-auto px-5 text-right">
        <div class="pb-5">{{ $contacts->links() }}</div>
    </div>
</div>

<x-table :headers="['Email', 'Status', '']">
    @foreach($contacts as $contact)
        <tr>
            <td class="table-cell">
                <a href="{{ route('contacts.edit', compact('contact')) }}">{{ $contact->email }}</a>
            </td>
            <td class="table-cell">
                <x-status-pill color="{{ $contact->unsubscribed ? 'red' : 'green' }}">
                    {{ $contact->unsubscribed ? 'Unsubscribed' : 'Subscribed' }}
                </x-status-pill>
            </td>
            <td class="table-cell text-sm text-right font-medium">
                <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="link">Edit</a>
            </td>
        </tr>
    @endforeach
</x-table>

<div class="py-5">{{ $contacts->links() }}</div>
