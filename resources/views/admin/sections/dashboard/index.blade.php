@extends('admin.layouts.master')

@push('css')

@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Dashboard")])
@endsection

@section('content')
    <div class="dashboard-area">
        <div class="dashboard-item-area">
            <div class="row">
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ $users }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeUsers }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart6" data-percent="{{ ($users != 0) ? intval(($activeUsers/$users)*100) : '0' }}">
                                    <span>
                                        @if($users != 0)
                                            {{ intval(($activeUsers/$users)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count"> {{ @$activeUsers }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Total") }} {{ @$users }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart7" data-percent="{{ ($users != 0) ? intval(($activeUsers/$users)*100) : '0' }}">
                                    <span>
                                        @if($users != 0)
                                            {{ intval(($activeUsers/$users)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Verified Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$verifiedUsers }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Total") }} {{ @$users }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart8" data-percent="{{ ($users != 0) ? intval(($verifiedUsers/$users)*100) : '0' }}">
                                    <span>
                                        @if($users != 0)
                                            {{ intval(($verifiedUsers/$users)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Banned Users") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$bannedUsers }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("Total") }} {{ @$users }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart9" data-percent="{{ ($users != 0) ? intval(($bannedUsers/$users)*100) : '0' }}">
                                    <span>
                                        @if($users != 0)
                                            {{ intval(($bannedUsers/$users)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Announcements") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$announcements }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeAnnouncements }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart14" data-percent="{{ ($announcements != 0) ? intval(($activeAnnouncements/$announcements)*100) : '0' }}">
                                    <span>
                                        @if($announcements != 0)
                                            {{ intval(($activeAnnouncements/$announcements)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active Announcements") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$activeAnnouncements }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$announcements }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart15" data-percent="{{ ($announcements != 0) ? intval(($activeAnnouncements/$announcements)*100) : '0' }}">
                                    <span>
                                        @if($announcements != 0)
                                            {{ intval(($activeAnnouncements/$announcements)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Banned Announcements") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$bannedAnnouncements }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$announcements }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart16" data-percent="{{ ($announcements != 0) ? intval(($bannedAnnouncements/$announcements)*100) : '0' }}">
                                    <span>
                                        @if($announcements != 0)
                                            {{ intval(($bannedAnnouncements/$announcements)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Subscribers") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$subscribers }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __("This Month") }} {{ $currentMonthSubscribers }}</span>
                                    <span class="badge badge--warning">{{ __("This Year") }} {{ $currentYearSubscribers }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart17" data-percent="{{ ($subscribers != 0) ? intval(($currentMonthSubscribers/$subscribers)*100) : '0' }}">
                                    <span>
                                        @if($subscribers != 0)
                                            {{ intval(($currentMonthSubscribers/$subscribers)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Messages") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$messages }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Replied") }} {{ @$repliedMessages }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart18" data-percent="65">
                                    <span>
                                        @if ($messages != 0)
                                            {{ intval(($repliedMessages / $messages) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Replied Messages") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$repliedMessages }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$messages }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart19" data-percent="{{ ($messages != 0) ? intval(($repliedMessages/$messages)*100) : '0' }}">
                                    <span>
                                        @if ($messages != 0)
                                            {{ intval(($repliedMessages / $messages) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Unanswered Messages") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$unansweredMessages }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$messages }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart20" data-percent="{{ ($unansweredMessages != 0) ? intval(($unansweredMessages/$messages)*100) : '0' }}">
                                    <span>
                                        @if($unansweredMessages != 0)
                                            {{ intval(($unansweredMessages/$messages)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Areas") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$areas }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeAreas }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart21" data-percent="{{ ($areas != 0) ? intval(($activeAreas/$areas)*100) : '0' }}">
                                    <span>
                                        @if ($areas != 0)
                                            {{ intval(($activeAreas / $areas) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active Areas") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$activeAreas }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$areas }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart22" data-percent="{{ ($areas != 0) ? intval(($activeAreas/$areas)*100) : '0' }}">
                                    <span>
                                        @if ($areas != 0)
                                            {{ intval(($activeAreas / $areas) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Banned Areas") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$bannedAreas }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$areas }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart23" data-percent="{{ ($bannedAreas != 0) ? intval(($bannedAreas/$areas)*100) : '0' }}">
                                    <span>
                                        @if($bannedAreas != 0)
                                            {{ intval(($bannedAreas/$areas)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Service Types") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$types }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeTypes }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart24" data-percent="{{ ($types != 0) ? intval(($activeTypes/$types)*100) : '0' }}">
                                    <span>
                                        @if ($types != 0)
                                            {{ intval(($activeTypes / $types) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active Service Types") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$activeTypes }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$types }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart25" data-percent="{{ ($types != 0) ? intval(($activeTypes/$types)*100) : '0' }}">
                                    <span>
                                        @if ($types != 0)
                                            {{ intval(($activeTypes / $types) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Banned Service Types") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$bannedTypes }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$types }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart26" data-percent="{{ ($bannedTypes != 0) ? intval(($bannedTypes/$types)*100) : '0' }}">
                                    <span>
                                        @if($bannedTypes != 0)
                                            {{ intval(($bannedTypes/$types)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Services") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$services }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeServices }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart27" data-percent="{{ ($services != 0) ? intval(($activeServices/$services)*100) : '0' }}">
                                    <span>
                                        @if ($services != 0)
                                            {{ intval(($activeServices / $services) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active services") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$activeServices }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$services }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart28" data-percent="{{ ($services != 0) ? intval(($activeServices/$services)*100) : '0' }}">
                                    <span>
                                        @if ($services != 0)
                                            {{ intval(($activeServices / $services) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Banned Services") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$bannedServices }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$services }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart29" data-percent="{{ ($bannedServices != 0) ? intval(($bannedServices/$services)*100) : '0' }}">
                                    <span>
                                        @if($bannedServices != 0)
                                            {{ intval(($bannedServices/$services)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Total Tickets") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$tickets }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Active") }} {{ @$activeTickets }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart30" data-percent="{{ ($tickets != 0) ? intval(($activeTickets/$tickets)*100) : '0' }}">
                                    <span>
                                        @if ($tickets != 0)
                                            {{ intval(($activeTickets / $tickets) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Active Tickets") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$activeTickets }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$tickets }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart31" data-percent="{{ ($tickets != 0) ? intval(($activeTickets/$tickets)*100) : '0' }}">
                                    <span>
                                        @if ($tickets != 0)
                                            {{ intval(($activeTickets / $tickets) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Pending Tickets") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$pendingTickets }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$tickets }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart32" data-percent="{{ ($pendingTickets != 0) ? intval(($pendingTickets/$tickets)*100) : '0' }}">
                                    <span>
                                        @if($pendingTickets != 0)
                                            {{ intval(($pendingTickets/$tickets)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __("Solved Tickets") }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ @$solvedTickets }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--success">{{ __("Total") }} {{ @$tickets }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart33" data-percent="{{ ($solvedTickets != 0) ? intval(($solvedTickets/$tickets)*100) : '0' }}">
                                    <span>
                                        @if($solvedTickets != 0)
                                            {{ intval(($solvedTickets/$tickets)*100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
