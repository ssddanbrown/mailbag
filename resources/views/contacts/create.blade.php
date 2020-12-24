<x-app-layout>
    <x-heading title="Contact" subtitle="Create new contact" :breadcrumbs="['Contacts' => route('contacts.index'), 'Create' => route('contacts.create')]"/>

    <div class="container">

        <form action="{{ route('contacts.store') }}" method="post" id="contact-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('contacts.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('contacts.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-button form="contact-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
