<x-app-layout title="Dashboard">

    <x-heading title="Welcome"/>

    <div class="container pb-20">

        <div class="mt-8">
            @include('dashboard.counts')
        </div>

        <div class="flex -mx-5 mb-5">
            <div class="flex-auto w-1/2 px-5">
                <h3 class="mb-4 text-2xl font-medium mt-10">Recent Sends</h3>
                @include('dashboard.recent-sends')
            </div>
            <div class="flex-auto w-1/2 px-5">
                <h3 class="mb-4 text-2xl font-medium mt-10">Latest Contacts</h3>
                @include('dashboard.latest-contacts')
            </div>
        </div>

        <div class="flex -mx-5 mb-5">
            <div class="flex-auto w-1/2 px-5">
                <h3 class="mb-4 text-2xl font-medium mt-10">Popular Lists</h3>
                @include('dashboard.popular-lists')
            </div>
            <div class="flex-auto w-1/2 px-5">
                <h3 class="mb-4 text-2xl font-medium mt-10">Upcoming RSS Checks</h3>
                @include('dashboard.upcoming-rss')
            </div>
        </div>


    </div>
</x-app-layout>
