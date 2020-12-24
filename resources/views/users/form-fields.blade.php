<div class="flex -mx-3">

    <div class="flex-auto px-3">
        <!-- Name -->
        <div class="pt-5">
            <x-label for="name" value="Name" />
            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $user->name ?? ''" required />
        </div>
    </div>

    <div class="flex-auto px-3">
        <!-- Email Address -->
        <div class="pt-5">
            <x-label for="email" value="Email" />
            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email') ?? $user->email ?? ''" required />
        </div>
    </div>

</div>

<!-- Password -->
<div class="pt-5">
    <x-label for="password" value="Password" />
    @if($user->id)
        <p class="text-sm mb-1 text-gray-600">
            Leave this blank to not change the password.
        </p>
    @endif
    <x-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password') ?? ''"/>
</div>
