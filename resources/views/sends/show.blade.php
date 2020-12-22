<x-app-layout>
    <x-heading title="Send" :subtitle="$send->name"/>

    <div class="container">

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto px-2">
                <x-status-pill-large :color="$send->activated ? 'green' : 'blue'" class="mr-1">
                    {{ $send->activated ? 'Launched' : 'Draft' }}
                </x-status-pill-large>
                <x-status-pill-large color="gray" class="mr-1" title="{{ $send->created_at }}">
                    Created {{ $send->created_at->diffForHumans() }}
                </x-status-pill-large>
            </div>
            <div class="flex-auto px-2 text-right">
                @if(!$send->activated)
                    <x-button-secondary-link :href="route('sends.edit', compact('send'))">Edit Send</x-button-secondary-link>
                    @include('sends.launch-button')
                @endif
            </div>
        </div>

        @if($send->activated)
            <div class="mb-10 bg-blue-50 border border-blue-300 p-5 text-blue-900">
                <h4 class="mt-1 text-2xl mb-1 font-semibold">Launch Details</h4>
                <p class="mb-1">
                    Send launched and sent to {{ $send->records->count() }} people
                    on the {{ $send->activated_at->format('jS \\of F Y \\a\\t h:i:s') }}
                </p>
            </div>
        @endif

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto w-1/2 px-2">
                <x-label>Name</x-label>
                <div>{{ $send->name }}</div>
            </div>
            <div class="flex-auto w-1/2 px-2">
                <x-label>Subject</x-label>
                <div>{{ $send->subject }}</div>
            </div>
        </div>

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto w-1/2 px-2">
                <x-label>Campaign</x-label>
                <div><a class="link" href="{{ route('campaigns.show', ['campaign' => $send->campaign]) }}">{{ $send->campaign->name }}</a></div>
            </div>
            <div class="flex-auto w-1/2 px-2">
                <x-label>Send List</x-label>
                <div>
                    <a href="{{ route('lists.edit', ['list' => $send->maillist]) }}" class="link">{{ $send->maillist->name }} &nbsp;<x-status-pill color="blue">{{ $send->maillist->contacts()->count() }} contacts</x-status-pill></a>
                </div>
            </div>
        </div>

        <x-label>Content</x-label>
        <div class="bg-white p-3 mt-1 border-gray-300 border rounded whitespace-pre">{{ $send->content }}</div>

    </div>

</x-app-layout>
