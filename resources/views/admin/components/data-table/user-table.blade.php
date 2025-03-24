<table class="custom-table user-search-table">
    <thead>
        <tr>
            <th></th>
            <th>{{ __("Username") }}</th>
            <th>{{ __("Email") }}</th>
            <th>{{ __("Phone") }}</th>
            <th>{{ __("Action") }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users ?? [] as $key => $item)
            <tr>
                <td>
                    <ul class="user-list">
                        <li><img src="{{ $item->userImage }}" alt="user"></li>
                    </ul>
                </td>
                <td><span>{{ $item->username }}</span></td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->full_mobile }}</td>
                <td>
                    @include('admin.components.link.info-default',[
                        'href'          => setRoute('admin.users.details', $item->username),
                        'permission'    => "admin.users.details",
                    ])
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty',['colspan' => 7])
        @endforelse
    </tbody>
</table>
