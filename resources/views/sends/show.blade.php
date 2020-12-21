<x-app-layout>
    <x-heading title="Send" :subtitle="$send->name"/>

    <div class="container">

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto px-2 text-right">
                <x-button-secondary-link :href="route('sends.edit', compact('send'))">Edit Send</x-button-secondary-link>
            </div>
        </div>

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
                <div><a href="{{ route('lists.edit', ['list' => $send->maillist]) }}" class="link">{{ $send->maillist->name }}</a></div>
            </div>
        </div>

        <x-label>Content</x-label>
        <div class="bg-white p-3 mt-1 border-gray-300 border rounded whitespace-pre">{{ $send->content }}</div>

    </div>

</x-app-layout>
