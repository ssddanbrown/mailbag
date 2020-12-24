<div class="bg-white px-5 py-3 shadow rounded-md">
    <div class="divide-y divide-gray-200">
        @foreach($popularLists as $list)
            <div class="py-2 flex">
                <div class="flex-auto">
                    <a href="{{ route('lists.show', compact('list')) }}" class="text-sm font-medium">{{ $list->name }}</a>
                    <div class="text-sm text-gray-500">Has {{ $list->contacts_count }} {{ \Illuminate\Support\Str::plural('contact', $list->lists_count) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
