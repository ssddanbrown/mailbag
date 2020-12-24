<x-app-layout>
    <x-heading title="Contact" :subtitle="$contact->email" :breadcrumbs="['Contacts' => route('contacts.index'), 'Edit' => route('contacts.edit', compact('contact'))]"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('contacts.update', ['contact' => $contact]) }}" method="post" id="contact-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('contacts.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('contacts.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('contacts.destroy', ['contact' => $contact])">
                Are you sure you want to delete this contact?
            </x-delete-dropdown>
            <x-button form="contact-form">Save</x-button>
        </div>

        <div class="border-t border-gray-300 mt-10">
            <x-subheading>Lists</x-subheading>
            @include('contacts.lists')
        </div>

    </div>

</x-app-layout>
