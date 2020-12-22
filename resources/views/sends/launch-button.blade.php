<div class="relative inline-block text-left mr-1" x-data="{ open: false }">
    <div>
        <x-button @click="open = true" type="button">Launch Send</x-button>
    </div>

    <x-dropdown-menu x-show="open" style="display: none;" @click.away="open = false">
        <form method="POST" action="{{ route('sends.launch', compact('send')) }}">
            {{ csrf_field() }}
            <div class="divide-y divide-gray-100">
                <p class="group px-4 py-2 text-indigo-800 text-sm">
                    This will send to everyone in the configured list. <br>
                    There are about {{ $send->maillist->contacts()->count() }} contacts in the list.
                    Are you sure you want to launch this send?
                </p>
                <button type="submit" class="group flex items-center block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-gray-100 hover:text-green-900 focus:outline-none focus:bg-green-100 focus:text-green-900 font-bold" role="menuitem">
                    <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path fill="#fff" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                    Confirm & Send
                </button>
            </div>
        </form>
    </x-dropdown-menu>
</div>
