<x-app-layout>
    <x-heading title="User" subtitle="Create new user" :breadcrumbs="['Users' => route('users.index'), 'Create' => route('users.create')]"/>

    <div class="container">

        <form action="{{ route('users.store') }}" method="post" id="user-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>
            @include('users.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('users.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-button form="user-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
