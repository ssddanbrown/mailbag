<x-app-layout title="Dashboard">

    <x-heading title="Welcome"/>

    <div class="container pb-20">

        <div class="mt-8">
            @include('dashboard.counts')
        </div>

        <div class="flex -mx-5 mb-5">
            <div class="flex-auto w-1/2 px-5">
                <x-subheading class="mb-4">Recent Sends</x-subheading>
                @include('dashboard.recent-sends')
            </div>
            <div class="flex-auto w-1/2 px-5">
                <x-subheading class="mb-4">Latest Contacts</x-subheading>
                @include('dashboard.latest-contacts')
            </div>
        </div>

        <div class="flex -mx-5 mb-5">
            <div class="flex-auto w-1/2 px-5">
                <x-subheading class="mb-4">Popular Lists</x-subheading>
                @include('dashboard.popular-lists')
            </div>
            <div class="flex-auto w-1/2 px-5">
                <x-subheading class="mb-4">Upcoming RSS Checks</x-subheading>
                @include('dashboard.upcoming-rss')
            </div>
        </div>


    </div>
</x-app-layout>
