<dl class="grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow divide-y divide-gray-200 md:grid-cols-3 md:divide-y-0 md:divide-x">
    <div>
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                Total Contacts
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-medium text-indigo-600">
                    {{ $counts['contacts'] }}
                </div>
            </dd>
        </div>
    </div>

    <div>
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                Sends Created
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-medium text-indigo-600">
                    {{ $counts['sends'] }}
                </div>
            </dd>
        </div>
    </div>

    <div>
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                Emails Sent
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-medium text-indigo-600">
                    {{ $counts['sent'] }}
                </div>
            </dd>
        </div>
    </div>
</dl>
