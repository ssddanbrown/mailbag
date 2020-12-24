<x-app-layout>

    <x-heading title="Lists" :breadcrumbs="['Lists' => route('lists.index')]"/>

    <div class="container mx-auto">

        <div class="flex align-middle -mx-5">
            <div class="flex-auto px-5">
                <x-search-input/>
            </div>
            <div class="flex-auto px-5 text-right">
                <x-button-secondary-link :href="route('lists.create')">Create New</x-button-secondary-link>
            </div>
        </div>

        <div class="pb-5">{{ $lists->links() }}</div>

        <x-table :headers="['Name', '', '']">
            @foreach($lists as $list)
                <tr>
                    <td class="table-cell">
                        <a href="{{ route('lists.show', compact('list')) }}" class="inline-block">
                            {{ $list->name }} <br>
                            <span class="text-gray-500 text-sm">{{ $list->display_name }}</span>
                        </a>
                    </td>
                    <td class="table-cell ">
                        <x-status-pill :color="$list->contacts_count == 0 ? 'red' : 'blue'">
                            {{ $list->contacts_count }} {{ \Illuminate\Support\Str::plural('contact', $list->contacts_count) }}
                        </x-status-pill>
                    </td>
                    <td class="table-cell text-sm font-medium text-right">
                        <a href="{{ route('lists.show', compact('list')) }}" class="link">View</a>
                        <span class="text-gray-500 px-1">|</span>
                        <a href="{{ route('signup.show', compact('list')) }}" target="_blank" class="link">Signup Form</a>
                        <span class="text-gray-500 px-1">|</span>
                        <a href="{{ route('lists.edit', compact('list')) }}" class="link">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $lists->links() }}</div>
    </div>

</x-app-layout>
