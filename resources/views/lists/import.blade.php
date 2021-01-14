<x-app-layout>
    <x-heading title="Import Into List" :subtitle="$list->name"  :breadcrumbs="['Lists' => route('lists.index'), $list->name => route('lists.show', compact('list')), 'Import' => route('lists.import.show', compact('list'))]"/>

    <div class="container">

        <form action="{{ route('lists.import.import', compact('list')) }}" method="post">
            {{ csrf_field() }}

            <div class="flex -mx-3 items-center mb-5">
                <div class="flex-auto px-3">
                    <p>
                        Add a list of email addresses below to import directly into this list,
                        with each email address on its own line.
                        Emails will be lower-cased upon entry.
                        <br>
                        Be sure that these contacts have shown actual intent to subscribe to this content.
                    </p>
                </div>
                <div class="px-3 text-right">
                    <x-button>Import</x-button>
                </div>
            </div>


            <x-textarea name="email_list" class="w-full" rows="12">{{ old('email_list') ?? '' }}</x-textarea>
        </form>

    </div>

</x-app-layout>
