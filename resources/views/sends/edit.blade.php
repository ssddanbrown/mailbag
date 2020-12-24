<x-app-layout>
    <x-heading title="Edit Send" :subtitle="$send->name" :breadcrumbs="['Campaigns' => route('campaigns.index'), $send->campaign->name => route('campaigns.show', ['campaign' => $send->campaign]), $send->name => route('sends.show', compact('send')), 'Edit Send' => route('sends.create')]"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('sends.update', ['send' => $send]) }}" method="post" id="send-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('sends.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('sends.show', compact('send')) }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('sends.destroy', ['send' => $send])">
                Are you sure you want to delete this send?
            </x-delete-dropdown>
            <x-button form="send-form">Save</x-button>
        </div>

        @include('sends.content-syntax')

    </div>

</x-app-layout>
