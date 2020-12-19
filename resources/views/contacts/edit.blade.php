<x-app-layout>

    <x-heading title="Contact" :subtitle="$contact->email"/>

    <div class="container mx-auto">

        <form action="{{ route('contacts.update', ['contact' => $contact]) }}" method="post">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <x-form-errors :errors="$errors"/>

            <div class="flex -mx-3">

                <div class="flex-auto px-3">
                    <!-- Email Address -->
                    <div class="pt-5">
                        <x-label for="email" value="Email" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email') ?? $contact->email ?? ''" required />
                    </div>
                </div>

                <div class="flex-auto px-3">
                    <!-- Email Address -->
                    <div class="pt-5">
                        <div class="relative flex justify-center items-start pt-5">
                            <div class="flex items-center h-5">
                                <x-checkbox id="unsubscribed" name="unsubscribed" value="1"
                                            :checked="intval(old('unsubscribed', $contact->unsubscribed)) === 1"/>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="unsubscribed" class="font-medium text-gray-700">Unsubscribed</label>
                                <p class="text-gray-500">Will not be sent any emails at all</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-10 text-right">
                <x-cancel-button href="{{ route('contacts.index') }}" class="mr-1"/>
                <x-button>Save</x-button>
            </div>

        </form>

    </div>

</x-app-layout>
