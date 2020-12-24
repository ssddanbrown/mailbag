@props(['color'])

<span {{ $attributes->merge(['class' => "border border-{$color}-300 px-2 inline-flex text-xs leading-5 font-medium rounded-full bg-{$color}-100 text-{$color}-800"]) }}
{{--    data-tailwind-hint-border="border-green-300 border-red-300 border-blue-300 border-gray-300 border-yellow-300"--}}
{{--    data-tailwind-hint-bg="bg-green-100 bg-red-100 bg-blue-100 bg-gray-100 bg-yellow-100"--}}
{{--    data-tailwind-hint-text="text-green-800 text-red-800 text-blue-800 text-gray-800 text-yellow-800"--}}
>{{ $slot }}</span>
