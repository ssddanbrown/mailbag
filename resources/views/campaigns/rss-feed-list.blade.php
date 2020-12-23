
<x-table :headers="['Send & URL', 'Status', 'Frequency & Last Check', '']">
    @foreach($campaign->rssFeeds as $feed)
        <tr>
            <td class="table-cell">
                {{ $feed->templateSend->name }}<br>
                <span class="text-sm text-gray-600">{{ $feed->url }}</span>
            </td>
            <td class="table-cell">
                <x-status-pill :color="$feed->active ? 'green' : 'red'">
                    {{ $feed->active ? 'Active' : 'Inactive' }}
                </x-status-pill>
            </td>
            <td class="table-cell">
                Every {{ $feed->send_frequency }} days <br>
                <span class="text-sm text-gray-600">Last Checked {{ is_null($feed->last_reviewed_at) ? 'Never' : $feed->last_reviewed_at->diffForHumans() }}</span>
            </td>
            <td class="table-cell text-sm font-medium text-right">
                <a href="{{ route('rss.edit', compact('campaign', 'feed')) }}" class="link">Edit</a>
            </td>
        </tr>
    @endforeach
</x-table>
