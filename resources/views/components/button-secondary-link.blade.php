<a {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-transparent border border-gray-300 rounded-md font-medium text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot->isEmpty() ? 'Cancel' : $slot }}
</a>
