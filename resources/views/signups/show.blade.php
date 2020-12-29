<x-guest-layout>
    @push('titles') Sign up to "{{ $list->display_name }}" @endpush

    <div class="container py-32 max-w-xl">
        <h1 class="text-4xl font-medium mb-2">{{ $list->display_name }}</h1>
        <p class="mb-5">{{ $list->description }}</p>

        <form action="{{ route('signup.signup', compact('list')) }}" method="post">
            {{ csrf_field() }}

            <x-form-errors :errors="$errors"/>

            <div class="mb-5">
                <x-label value="Email Address" for="email" class="mb-2"/>
                <x-input type="email" name="email" id="email" value="{{ old('email', '') }}" class="w-full" required/>
            </div>

            @if(config('services.hcaptcha.active'))
                <div class="mb-5">
                    @push('scripts')
                        <script src="https://hcaptcha.com/1/api.js" async defer></script>
                    @endpush
                        <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.sitekey') }}"></div>
                </div>
            @endif

            <p class="text-sm text-gray-700 mb-5">
                You will receive an email to confirm you own the provided email address.
                You will need to click through on this email and confirm your sign-up before you're actually subscribed.
            </p>
            <x-button class="w-full justify-center font-bold">Signup</x-button>
        </form>

        <div class="mt-5 pt-5 border-t border-gray-300 text-sm text-gray-700 space-y-2">
            @include('signups.disclaimer')
        </div>
    </div>

</x-guest-layout>
