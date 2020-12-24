<x-app-layout>

    <x-heading title="Contacts" :breadcrumbs="['Contacts' => route('contacts.index')]"/>

    <div class="container mx-auto">

        <div class="flex align-middle -mx-5">
            <div class="flex-auto px-5">
                <x-search-input/>
            </div>
            <div class="flex-auto px-5 text-right">
                <x-button-secondary-link :href="route('contacts.create')">Create New</x-button-secondary-link>
            </div>
        </div>

        <div class="pb-5">{{ $contacts->links() }}</div>

        <x-table :headers="['Email', 'Status', '', '']">
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
                    <td class="table-cell text-sm text-gray-700">
                        <x-status-pill color="blue">
                            {{ $contact->lists_count }}
                            {{ \Illuminate\Support\Str::plural('list', $contact->lists_count) }}
                        </x-status-pill>
                    </td>
                    <td class="table-cell text-sm text-right font-medium">
                        <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="link">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $contacts->links() }}</div>
    </div>

</x-app-layout>
