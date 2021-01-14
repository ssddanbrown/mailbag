<x-app-layout>
    <x-heading title="List" :subtitle="$list->name"  :breadcrumbs="['Lists' => route('lists.index'), $list->name => route('lists.show', compact('list'))]"/>

    <div class="container">

        <div class="flex -mx-2 mb-5">
            <div class="flex-auto px-2 text-right">
                <x-button-secondary-link :href="route('lists.import.show', compact('list'))">Import to List</x-button-secondary-link>
                <x-button-secondary-link :href="route('lists.edit', compact('list'))">Edit List</x-button-secondary-link>
            </div>
        </div>

        <div class="flex -mx-3 border-t border-gray-300">

            <div class="flex-auto w-1/2 px-3">
                <!-- List Name -->
                <div class="pt-5">
                    <x-label value="List Name" />
                    <div>{{ $list->name }}</div>
                </div>

                <!-- Display Name -->
                <div class="pt-5">
                    <x-label value="Display Name" />
                    <div>{{ $list->display_name }}</div>
                </div>

                <!-- URL -->
                <div class="pt-5">
                    <x-label value="Sign-up URL" />
                    <div><a target="_blank" href="{{ route('signup.show', compact('list')) }}" class="link">/{{ $list->slug }}</a></div>
                </div>
            </div>

            <div class="flex-auto w-1/2 px-3">
                <!-- Description -->
                <div class="pt-5">
                    <x-label value="Description" />
                    <div>{{ $list->description }}</div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-300 mt-10">
            <x-subheading>Contacts</x-subheading>
            @include('lists.contacts')
        </div>

    </div>

</x-app-layout>
