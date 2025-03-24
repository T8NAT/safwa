<table class="custom-table subscribers-table">
    <thead>
        <tr>
            <th>{{ __("Email") }}</th>
            <th>{{ __("Reply") }}</th>
            <th>{{ __("Created At") }}</th>
            <th>{{ __("Action") }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($subscribers ?? [] as $item)
            <tr data-item="{{ $item->editData }}">
                <td>{{ $item->email }}</td>
                <td>
                    @if ($item->reply == 0)
                    <span class="badge badge--danger">{{ __("Not Replied") }}</span>
                    @else
                    <span class="badge badge--success">{{ __("Replied") }}</span>
                    @endif
                </td>
                <td>{{ $item->created_at}}</td>
                <td>
                    @include('admin.components.link.custom',[
                        'href'          => "#message-reply",
                        'class'         => "btn btn--base edit-modal-button modal-btn",
                        'icon'          => "las la-envelope-open-text",
                        'permission'    => "admin.subscriber.reply",
                    ])
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty',['colspan' => 7])
        @endforelse
    </tbody>
</table>

@push("script")
    <script>

    </script>
@endpush
