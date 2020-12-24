@props(['color'])

<span {{ $attributes->merge(['class' => "px-2 inline-flex text-md py-1 border border-{$color}-400 leading-5 font-medium rounded-full bg-{$color}-100 text-{$color}-800"]) }}>{{ $slot }}</span>
