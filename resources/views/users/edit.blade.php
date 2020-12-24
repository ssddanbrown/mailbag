<x-app-layout>
    <x-heading title="User" :subtitle="$user->email" :breadcrumbs="['Users' => route('users.index'), 'Edit' => route('users.edit', compact('user'))]"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('users.update', ['user' => $user]) }}" method="post" id="user-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('users.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('users.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('users.destroy', ['user' => $user])">
                Are you sure you want to delete this user?
            </x-delete-dropdown>
            <x-button form="user-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
