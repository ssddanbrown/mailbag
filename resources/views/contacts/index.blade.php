<x-app-layout>

    <x-heading title="Contacts"/>

    <div class="container mx-auto">

        <x-search-input/>

        <div class="pb-5">{{ $contacts->links() }}</div>

        <x-table :headers="['Email', 'Status', '']">
            @foreach($contacts as $contact)
                <tr>
                    <td class="table-cell">{{ $contact->email }}</td>
                    <td class="table-cell">
                        <x-status-pill color="{{ $contact->unsubscribed ? 'red' : 'green' }}">
                            {{ $contact->unsubscribed ? 'Unsubscribed' : 'Subscribed' }}
                        </x-status-pill>
                    </td>
                    <td class="table-cell text-sm font-medium">
                        <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $contacts->links() }}</div>
    </div>

</x-app-layout>
