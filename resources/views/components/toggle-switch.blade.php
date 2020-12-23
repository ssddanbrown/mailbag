@props(['value'])

<button type="button" aria-pressed="false" class="{{ $value ? 'bg-indigo-600' : 'bg-gray-200' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" x-data="{'active': {{ $value ? 'true' : 'false' }}}" @click="active = !active" :class="{'bg-indigo-600': active, 'bg-gray-200': !active}">
    <span class="sr-only">Use setting</span>
    <!-- On: "translate-x-5", Off: "translate-x-0" -->
    <span aria-hidden="true" class="{{ $value ? 'translate-x-5' : 'translate-x-0' }} inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200" :class="{'translate-x-5': active, 'translate-x-0': !active}"></span>
    <input type="hidden" {{ $attributes->merge(['name' => '', 'value' => $value]) }} :value="active ? '1' : '0'">
</button>
