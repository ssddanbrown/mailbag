<div class="bg-white px-5 py-3 shadow rounded-md">
    <div class="divide-y divide-gray-200">
        @foreach($recentSends as $send)
            <div class="py-2 flex">
                <div class="flex-auto">
                    <a href="{{ route('sends.show', compact('send')) }}" class="text-sm font-medium">{{ $send->name }}</a> <br>
                    <a href="{{ route('campaigns.show', ['campaign' => $send->campaign]) }}" class="text-sm text-gray-500 hover:underline">Campaign: {{ $send->campaign->name }}</a>
                    <div class="text-sm text-gray-500" title="{{ $send->activated_at }}">Sent {{ $send->activated_at->diffForHumans() }} to {{ $send->records_count }} contacts</div>

                </div>
            </div>
        @endforeach
    </div>
</div>
