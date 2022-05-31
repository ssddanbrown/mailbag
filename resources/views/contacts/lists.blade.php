<div class="flex -mx-5">
    <div class="flex-auto px-5 py-5">
        @if($contact->lists->count() > 0)
            <div class="rounded-md shadow-sm block w-full divide-y divide-gray-200 bg-white">
                @foreach($contact->lists as $list)
                    <div class="px-3 py-2 flex">
                        <div class="flex-auto">
                            <a href="{{ route('lists.show', compact('list')) }}">{{ $list->name }}</a> <br>
                            <span class="text-sm text-gray-500">Subscribed {{ $list->pivot->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="shrink pl-1">
                            <x-delete-dropdown :route="route('contacts.lists.remove', ['contact' => $contact])">
                                <x-slot name="extraFields">
                                    <input type="hidden" name="lists[]" value="{{ $list->id }}">
                                </x-slot>
                                Are you sure you want to remove this list from this contact?
                            </x-delete-dropdown>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="italic">This contact is not subscribed to any lists.</p>
        @endif
    </div>
    <div class="flex-auto w-1/3 px-5">
        <form action="{{ route('contacts.lists.add', compact('contact')) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <x-label for="lists" value="Add to lists" />
            <x-select-list :value="old('list', '')"
                           :options="$listOptions"
                           multiple
                           class="block mt-1 w-full" id="lists" name="lists[]" required/>
            <div class="text-right pt-4">
                <x-button>Add</x-button>
            </div>
        </form>
    </div>
</div>
