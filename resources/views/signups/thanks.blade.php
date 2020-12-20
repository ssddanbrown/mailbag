<x-guest-layout>
    @push('titles') Thank you for signing up @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">Thank you for signing up</h1>
        <p class="mb-5">You requested to subscribe to "{{ $list->display_name }}"</p>

        <p class="mb-5 bg-blue-50 px-5 py-3 border border-blue-400 rounded text-blue-900">
            You will shortly receive an email to confirm you own the provided email address.
            You will need to click through on this email and confirm your sign-up before you're actually subscribed.
        </p>
    </div>

</x-guest-layout>
