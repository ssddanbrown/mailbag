@if($title ?? false)
    @push('titles')| {{ $title }}@endpush
    <div {{ $attributes->merge(['class' => 'container mt-8']) }}>
        <div class="border-b border-gray-300 py-5 pb-5 mb-5">
            <h2 class="font-bold text-4xl text-gray-800 leading-tight">
                {{ $title }}
            </h2>
            @if($subtitle ?? false)
                @push('titles') | {{ $subtitle }}@endpush
                <h2 class="font-semibold text-xl mt-1 mb-1 text-gray-600 leading-tight">
                    {{ $subtitle }}
                </h2>
            @endif
        </div>
    </div>
@endif
