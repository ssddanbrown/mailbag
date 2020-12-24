<x-app-layout>
    <x-heading title="Campaign" subtitle="Create new campaign" :breadcrumbs="['Campaigns' => route('campaigns.index'), 'Create' => route('campaigns.create')]"/>

    <div class="container">

        <form action="{{ route('campaigns.store') }}" method="post" id="campaign-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('campaigns.form-fields')
        </form>

        <div class="pt-10 text-right">
            <x-button-secondary-link href="{{ route('campaigns.index') }}" class="mr-1">Cancel</x-button-secondary-link>
            <x-button form="campaign-form">Save</x-button>
        </div>

    </div>

</x-app-layout>
