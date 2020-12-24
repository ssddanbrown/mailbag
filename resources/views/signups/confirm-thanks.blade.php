<x-guest-layout>
    @push('titles') Thank you for confirming your sign up @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">Thank you</h1>
        <p class="mb-5">You have now confirmed your signup to:<br>"{{ $list->display_name }}"</p>

        <p class="">As a reminder, here are the details of this list:</p>

        <p class="mb-5 text-gray-600 italic">{{ $list->description }}</p>
    </div>

</x-guest-layout>
