<x-app-layout>
    <x-heading title="Campaign" :subtitle="$campaign->name"/>

    <div class="container">

        <div class="flex -mx-2">
            <div class="flex-auto px-2">

            </div>
            <div class="flex-auto px-2 text-right">
                <x-button-secondary-link :href="route('campaigns.edit', compact('campaign'))">Edit Campaign</x-button-secondary-link>
            </div>
        </div>

    </div>

</x-app-layout>
