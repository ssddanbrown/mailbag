<x-app-layout>

    <x-heading title="Users" :breadcrumbs="['Users' => route('users.index')]"/>

    <div class="container mx-auto">

        <div class="flex align-middle -mx-5">
            <div class="flex-auto px-5">
                <x-search-input/>
            </div>
            <div class="flex-auto px-5 text-right">
                <x-button-secondary-link :href="route('users.create')">Create New</x-button-secondary-link>
            </div>
        </div>

        <div class="pb-5">{{ $users->links() }}</div>

        <x-table :headers="['Name & Email', '']">
            @foreach($users as $user)
                <tr>
                    <td class="table-cell">
                        <a href="{{ route('users.edit', compact('user')) }}">
                            {{ $user->name }} <br>
                            <span class="text-sm text-gray-600">{{ $user->email }}</span>
                        </a>
                    </td>
                    <td class="table-cell text-sm text-right font-medium">
                        <a href="{{ route('users.edit', ['user' => $user]) }}" class="link">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-table>

        <div class="py-5">{{ $users->links() }}</div>
    </div>

</x-app-layout>
