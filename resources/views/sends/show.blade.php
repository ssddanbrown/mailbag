<x-app-layout>
    <x-heading title="Send" :subtitle="$send->name"/>

    <div class="container">

        <div class="flex -mx-2">
            <div class="flex-auto px-2">

            </div>
            <div class="flex-auto px-2 text-right">
                <x-button-secondary-link :href="route('sends.edit', compact('send'))">Edit Send</x-button-secondary-link>
            </div>
        </div>

    </div>

</x-app-layout>
