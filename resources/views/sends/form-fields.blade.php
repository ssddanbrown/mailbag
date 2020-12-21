<div class="flex -mx-3">

    <div class="flex-auto w-1/2 px-3">
        <!-- Send Name -->
        <div class="pt-5">
            <x-label for="name" value="Name" />
            <x-input id="name" class="block mt-1 w-full" name="name" :value="old('name') ?? $send->name ?? ''" required />
        </div>
    </div>

    <div class="flex-auto w-1/2 px-3">
        <!-- Subject -->
        <div class="pt-5">
            <x-label for="subject" value="Subject" />
            <x-input id="subject" class="block mt-1 w-full" name="subject" :value="old('subject') ?? $send->subject ?? ''" required />
        </div>
    </div>

</div>

<div class="flex -mx-3">

    <div class="flex-auto w-1/2 px-3">
        <!-- Campaign -->
        <div class="pt-5">
            <x-label for="campaign_id" value="Campaign" />
            <x-select-list id="campaign_id" class="block mt-1 w-full"
                      name="campaign_id"
                      :value="old('campaign_id') ?? request()->get('campaign') ?? $send->campaign_id ?? ''"
                      :options="\App\Models\Campaign::getAllForSelect()"
                      required />
        </div>
    </div>

    <div class="flex-auto w-1/2 px-3">
        <!-- List -->
        <div class="pt-5">
            <x-label for="mail_list_id" value="List" />
            <x-select-list id="mail_list_id" class="block mt-1 w-full"
                      name="mail_list_id"
                      :value="old('mail_list_id') ?? $send->mail_list_id ?? ''"
                      :options="\App\Models\MailList::getAllForSelect()"
                      required />
        </div>
    </div>

</div>

<!-- Content -->

@push('head')
    <script src="{{ url('libs/codemirror/codemirror.js') }}"></script>
    <link rel="stylesheet" href="{{ url('libs/codemirror/codemirror.css') }}">
@endpush

<div class="pt-5">
    <x-label for="content" value="Content" class="mb-1" />
    <div class="rounded-md overflow-hidden shadow-sm border border-gray-300">
        <x-textarea id="content" class="block mt-1 w-full" name="content" :value="old('content') ?? $send->content ?? ''" required />
    </div>
    <script>
        const textarea = document.getElementById('content');
        const editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
        });
    </script>
</div>
