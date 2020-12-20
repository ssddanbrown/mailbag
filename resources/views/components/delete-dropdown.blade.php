@props(['route'])

<div class="relative inline-block text-left mr-1" x-data="{ open: false }">
    <div>
        <x-button-secondary @click="open = true" type="button">Delete</x-button-secondary>
    </div>

    <x-dropdown-menu x-show="open" style="display: none;" @click.away="open = false">
        <form method="POST" action="{{ $route }}">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            {{ $extraFields ?? '' }}
            <div class="divide-y divide-gray-100">
                <p class="group px-4 py-2 text-red-600 text-sm">{{ $slot }}</p>
                <button type="submit" class="group flex items-center block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Confirm
                </button>
            </div>
        </form>
    </x-dropdown-menu>
</div>
