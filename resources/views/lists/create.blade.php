<x-app-layout>
    <x-heading title="List" subtitle="Create new list" :breadcrumbs="['Lists' => route('lists.index'), 'Create' => route('lists.create')]"/>

    <div class="container">

        <form action="{{ route('lists.store') }}" method="post" id="list-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('lists.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('lists.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-button form="list-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
