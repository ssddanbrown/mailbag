@if($title ?? false)
    <div {{ $attributes->merge(['class' => 'container py-5 mt-8']) }}>
        <h2 class="font-bold text-4xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
        @if($subtitle ?? false)
            <h2 class="font-semibold text-xl mt-1 text-gray-600 leading-tight">
                {{ $subtitle }}
            </h2>
        @endif
    </div>
@endif
