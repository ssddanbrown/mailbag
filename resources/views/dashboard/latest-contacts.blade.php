<div class="bg-white px-5 py-3 shadow rounded-md">
    <div class="divide-y divide-gray-200">
        @foreach($latestContacts as $contact)
            <div class="py-2 flex">
                <div class="flex-auto">
                    <div class="float-right text-xs opacity-60 font-bold uppercase {{ $contact->unsubscribed ? 'text-red-500' : 'text-green-500' }}">
                        {{ $contact->unsubscribed ? 'Unsubscribed' : 'Subscribed' }}
                    </div>
                    <a href="{{ route('contacts.edit', compact('contact')) }}" class="text-sm font-medium">{{ $contact->email }}</a>
                    <div class="text-sm text-gray-500">On {{ $contact->lists_count }} {{ \Illuminate\Support\Str::plural('list', $contact->lists_count) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
