<x-guest-layout>
    @push('titles') Unsubscribe @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">Unsubscribe</h1>
        <p class="mb-5">
            Sorry to see you go.
            You can choose to be removed from the list that was used to send
            the email you clicked through on or
            you can unsubscribe from all {{ config('app.company_name') }} emails:
        </p>

        <div class="border-t border-gray-300 pt-5">
            <div class="md:flex -mx-3 items-center">
                <div class="flex-auto w-full md:w-2/3 px-3">
                    <h4 class="font-bold text-xl">Remove me from this list</h4>
                    <p class="italic text-gray-700 text-sm">{{ $sendRecord->send->maillist->name }}</p>
                </div>
                <div class="flex-auto w-full md:w-1/3 px-3 py-2 md:text-right">
                    <form action="{{ route('unsubscribe.list', compact('sendRecord')) }}" method="post">
                        {{ csrf_field() }}
                        <x-button>Remove</x-button>
                    </form>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-300 pt-5 mt-5">
            <div class="md:flex -mx-3 items-center">
                <div class="flex-auto w-full md:w-2/3 px-3">
                    <h4 class="font-bold text-xl">Unsubscribe from all</h4>
                    <p class="italic text-gray-700 text-sm">
                        Unsubscribe from all emails sent from {{ config('app.company_name') }}
                    </p>
                </div>
                <div class="flex-auto w-full md:w-1/3 px-3 py-2 md:text-right">
                    <form action="{{ route('unsubscribe.all', compact('sendRecord')) }}" method="post">
                        {{ csrf_field() }}
                        <x-button>Unsubscribe</x-button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-guest-layout>
