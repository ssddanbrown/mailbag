@props(['color'])

<span {{ $attributes->merge(['class' => "px-2 inline-flex text-md py-1 border border-{$color}-400 leading-5 font-medium rounded-full bg-{$color}-100 text-{$color}-800"]) }}
    {{--    data-tailwind-hint-border="border-green-400 border-red-400 border-blue-400 border-gray-400 border-yellow-400"--}}
>{{ $slot }}</span>
