<x-app-layout>
    <x-heading title="List" :subtitle="$list->name"  :breadcrumbs="['Lists' => route('lists.index'), 'Edit' => route('lists.edit', compact('list'))]"/>

    <div class="container">

        <form action="{{ route('lists.update', ['list' => $list]) }}" method="post" id="list-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('lists.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('lists.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('lists.destroy', ['list' => $list])">
                Are you sure you want to delete this list?
            </x-delete-dropdown>
            <x-button form="list-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
