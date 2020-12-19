<table class="min-w-full divide-y divide-gray-200 max-w-full table-fixed">
    <thead class="bg-gray-50">
    @foreach($headers as $header)
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
    @endforeach
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    {{ $slot }}
    </tbody>
</table>
