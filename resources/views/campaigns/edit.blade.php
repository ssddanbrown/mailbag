<x-app-layout>
    <x-heading title="Edit Campaign" :subtitle="$campaign->name" :breadcrumbs="['Campaigns' => route('campaigns.index'), $campaign->name => route('campaigns.show', compact('campaign')), 'Edit' => route('campaigns.edit', compact('campaign'))]"/>

    <div class="container">

        <x-subheading>Details</x-subheading>

        <form action="{{ route('campaigns.update', ['campaign' => $campaign]) }}" method="post" id="campaign-form">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('campaigns.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('campaigns.show', compact('campaign')) }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-delete-dropdown :route="route('campaigns.destroy', ['campaign' => $campaign])">
                Are you sure you want to delete this campaign? All feeds and sends will also be deleted.
            </x-delete-dropdown>
            <x-button form="campaign-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
