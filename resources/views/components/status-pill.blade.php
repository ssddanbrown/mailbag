@props(['color'])

<span {{ $attributes->merge(['class' => "border border-{$color}-300 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{$color}-100 text-{$color}-800"]) }}>{{ $slot }}</span>
