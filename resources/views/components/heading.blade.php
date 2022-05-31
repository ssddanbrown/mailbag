@props(['title', 'subtitle', 'breadcrumbs'])

@if($title ?? false)
    @push('titles')| {{ $title }}@endpush
    <div {{ $attributes->merge(['class' => 'container mt-8']) }}>
        <div class="border-b border-gray-300 pb-5 mb-5">
            @if(count($breadcrumbs ?? []) > 0)
                <!-- This example requires Tailwind CSS v2.0+ -->
                    <nav class="flex mb-5 -mt-1" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <div>
                                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                        <svg class="shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        <span class="sr-only">Home</span>
                                    </a>
                                </div>
                            </li>
                            @foreach($breadcrumbs as $text => $link)
                            <li>
                                <div class="flex items-center">
                                    <svg class="shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ $link }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $text }}</a>
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </nav>
            @endif
            <h2 class="font-bold text-4xl text-gray-800 leading-tight">
                {{ $title }}
            </h2>
            @if($subtitle ?? false)
                @push('titles') | {{ $subtitle }}@endpush
                <h2 class="font-medium text-xl mt-1 mb-1 text-gray-600 leading-tight">
                    {{ $subtitle }}
                </h2>
            @endif
        </div>
    </div>
@endif
