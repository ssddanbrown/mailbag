<x-guest-layout>
    @push('titles') {{ $type === 'all' ? 'Unsubscribe' : 'List Removal' }} Confirmed @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">
            {{ $type === 'all' ? 'Unsubscribe from all' : 'List Removal' }} Confirmed
        </h1>
        @if($type === 'all')
            <p class="mb-5">
                You have now been unsubscribed from our mailing so you should no longer
                receive our content unless you sign-up again.
            </p>
        @else
            <p class="mb-5">
                You have been removed from the requested list so will no longer
                receive that content. You may still be sent other emails from us.
            </p>
        @endif

    </div>

</x-guest-layout>
