@props(['options' => [], 'disabled' => false, 'value' => null])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'text']) !!}>
    @foreach($options as $optionVal => $label)
        <option @if($value == $optionVal) selected="selected" @endif value="{{ $optionVal }}">{{ $label }}</option>
    @endforeach
</select>
