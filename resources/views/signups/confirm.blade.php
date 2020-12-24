<x-guest-layout>
    @push('titles') Confirm your sign up to "{{ $signup->maillist->display_name }}" @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">Confirm your sign up</h1>
        <p class="mb-5">For "{{ $signup->maillist->display_name }}"</p>

        <p class="">As a reminder, here are the details of this list:</p>

        <p class="mb-5 text-gray-600 italic">{{ $signup->maillist->description }}</p>

        <form action="{{ route('signup.confirm.confirm', compact('signup')) }}" method="post">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>
            <x-button class="w-full justify-center font-bold">Confirm</x-button>
        </form>

        <div class="mt-5 pt-5 border-t border-gray-300 text-sm text-gray-700 space-y-2">
            @include('signups.disclaimer')
        </div>
    </div>

</x-guest-layout>
