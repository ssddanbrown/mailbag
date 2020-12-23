<div class="flex -mx-3">

    <div class="flex-auto w-3/4 px-3 pt-5">
        <!-- Feed URL -->
        <x-label for="url" value="Feed URL" />
        <x-input id="url" class="block mt-1 w-full" name="url" :value="old('url') ?? $feed->url ?? ''" required />
    </div>

    <div class="flex-auto w-1/4 px-3 pt-5 text-right">
        <!-- Active -->
        <x-label for="active" value="Active" class="mb-2" />
        <x-toggle-switch id="active" name="active" :value="old('active') ?? $feed->active ?? '0'" />
    </div>

</div>

<div class="flex -mx-3">
    <div class="flex-auto w-1/3 px-3 pt-5">
        <!-- Template Send -->
        <x-label for="template_send_id" value="Template Send" />
        <p class="text-sm py-1 text-gray-600">
            Select the send you want to use as a template.
            When sending a new RSS update, the content and details will be copied from this send.
        </p>
        <x-select-list id="template_send_id" class="block mt-1 w-full"
                       name="template_send_id"
                       :options="$campaign->getSendsForSelect()"
                       :value="old('template_send_id') ?? $feed->template_send_id ?? ''" required />
    </div>
</div>

<div class="flex -mx-3">

    <div class="flex-auto w-1/2 px-3 pt-5">
        <!-- Send Frequency -->
        <x-label for="target_hour" value="Target Hour (24 hr)" />
        <p class="text-sm py-1 text-gray-600">
            Set an hour to send the mail in. Not a guarantee but the system uses this as a target
            so mail is sent around the same time each send.
        </p>
        <x-input id="target_hour"
                 class="block mt-1 w-full"
                 name="target_hour"
                 type="number" required
                 min="0" step="1" max="23"
                 :value="old('target_hour') ?? $feed->target_hour ?? '14'" />
    </div>

    <div class="flex-auto w-1/2 px-3 pt-5">
        <!-- Send Frequency -->
        <x-label for="send_frequency" value="Send Frequency (In Days)" />
        <p class="text-sm py-1 text-gray-600">
            Choose how often you want to check for new RSS items.
            This is effectively how often updates are sent assuming there's always new content.
        </p>
        <x-input id="send_frequency"
                 class="block mt-1 w-full"
                 name="send_frequency"
                 type="number" required
                 min="1" step="1" max="366"
                 :value="old('send_frequency') ?? $feed->send_frequency ?? '7'" />
    </div>

</div>
