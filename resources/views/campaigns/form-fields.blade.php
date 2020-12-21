<div class="flex -mx-3">

    <div class="flex-auto px-3">
        <!-- Campaign Name -->
        <div class="pt-5">
            <x-label for="name" value="Name" />
            <x-input id="name" class="block mt-1 w-full" name="name" :value="old('name') ?? $campaign->name ?? ''" required />
        </div>
    </div>

</div>
