<x-app-layout>
    <x-heading title="Send" subtitle="Create new send" :breadcrumbs="array_merge($campaign ? ['Campaigns' => route('campaigns.index'), $campaign->name => route('campaigns.show', compact('campaign'))] : [], ['New Send' => route('sends.create')])"/>

    <div class="container">

        <form action="{{ route('sends.store') }}" method="post" id="send-form">
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            @include('sends.form-fields')
        </form>

        <div class="pt-10 text-right">
            @if(request()->has('campaign'))
                <x-button-secondary-link href="{{ route('campaigns.show', compact('campaign')) }}" class="mr-1">Cancel</x-button-secondary-link>
            @endif
            <x-button form="send-form">Save</x-button>
        </div>

        @include('sends.content-syntax')

    </div>

</x-app-layout>
