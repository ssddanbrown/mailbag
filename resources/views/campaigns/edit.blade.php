<x-app-layout>
    <x-heading title="Campaign" :subtitle="$campaign->email"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('campaigns.update', ['campaign' => $campaign]) }}" method="post" id="campaign-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('campaigns.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('campaigns.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('campaigns.destroy', ['campaign' => $campaign])">
                Are you sure you want to delete this campaign?
            </x-delete-dropdown>
            <x-button form="campaign-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
