<div class="flex -mx-3">

    <div class="flex-auto px-3">
        <!-- List Name -->
        <div class="pt-5">
            <x-label for="name" value="List Name" />
            <p class="text-xs text-gray-500">
                The name used for your own reference.
            </p>
            <x-input id="name" name="name" :value="old('name') ?? $list->name ?? ''" required class="block mt-1 w-full"  />
        </div>

        <!-- Display Name -->
        <div class="pt-5">
            <x-label for="display_name" value="Display Name" />
            <p class="text-xs text-gray-500">
                The name that will be shown to contacts on things like the sign-up form.
            </p>
            <x-input id="display_name" name="display_name" :value="old('display_name') ?? $list->display_name ?? ''" required class="block mt-1 w-full"  />
        </div>

        <!-- Slug -->
        <div class="pt-5">
            <x-label for="slug" value="URL Slug" />
            <p class="text-xs text-gray-500">
                Used in the URLs for the sign-up form. <br>
                If this is left empty the URL slug will be automatically generated
                from the display name.
            </p>
            <x-input id="slug" name="slug" :value="old('slug') ?? $list->slug ?? ''" class="block mt-1 w-full"  />
        </div>
    </div>

    <div class="flex-auto px-3">
        <!-- Slug -->
        <div class="pt-5">
            <x-label for="description" value="Description" />
            <p class="text-xs text-gray-500">
                This will be shown to contacts to describe what kind of content
                they will receive.
            </p>
            <x-textarea id="description" name="description" :value="old('description') ?? $list->description ?? ''" rows="8" class="block mt-1 w-full"  />
        </div>
    </div>
</div>
