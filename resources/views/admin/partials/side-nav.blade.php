<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{ setRoute('admin.dashboard') }}" class="sidebar-main-logo">
                <img src="{{ get_logo($basic_settings) }}" data-white_img="{{ get_logo($basic_settings,'white') }}"
                data-dark_img="{{ get_logo($basic_settings,'dark') }}" alt="logo">
            </a>
            <button class="sidebar-menu-bar">
                <i class="fas fa-exchange-alt"></i>
            </button>
        </div>
        <div class="sidebar-user-area">
            <div class="sidebar-user-thumb">
                <a href="{{ setRoute('admin.profile.index') }}"><img src="{{ get_image(Auth::user()->image,'admin-profile','profile') }}" alt="user"></a>
            </div>
            <div class="sidebar-user-content">
                <h6 class="title">{{ Auth::user()->fullname }}</h6>
                <span class="sub-title">{{ Auth::user()->getRolesString() }}</span>
            </div>
        </div>
        @php
            $current_route = Route::currentRouteName();
        @endphp
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.dashboard',
                    'title'     =>  __("Dashboard"),
                    'icon'      => "menu-icon las la-rocket",
                ])

                 {{-- Section Defaults --}}
                 @include('admin.components.side-nav.link-group',[
                    'group_title'       =>  __("Defaults"),
                    'group_links'       => [
                        [
                            'title'     => __("Setup Currency"),
                            'route'     => "admin.currency.index",
                            'icon'      => "menu-icon las la-coins",
                        ],
                        [
                            'title'     => __("Contact Messages"),
                            'route'     => "admin.contact.index",
                            'icon'      => "menu-icon las la-inbox",
                        ],
                        [
                            'title'     => __("Subscribers"),
                            'route'     => "admin.subscriber.index",
                            'icon'      => "menu-icon las la-bell",
                        ],
                    ]
                ])

                 {{-- Car Section --}}
{{--            @include('admin.components.side-nav.link-group',[--}}
{{--                    'group_title'       => __("Cars"),--}}
{{--                    'group_links'       => [--}}
{{--                        [--}}
{{--                            'title'     => __("Car Types"),--}}
{{--                            'route'     => "admin.car.types.index",--}}
{{--                            'icon'      => "menu-icon las la-calendar-day",--}}
{{--                        ],--}}
{{--                        [--}}
{{--                            'title'     => __("Car Areas"),--}}
{{--                            'route'     => "admin.car.area.index",--}}
{{--                            'icon'      => "menu-icon las la-map-marker-alt",--}}
{{--                        ],--}}
{{--                        [--}}
{{--                            'title'     => __("Cars"),--}}
{{--                            'route'     => "admin.car.index",--}}
{{--                            'icon'      => "menu-icon las la-car",--}}
{{--                        ],--}}
{{--                    ]--}}
{{--                ])--}}

                {{-- Service Section --}}
                @include('admin.components.side-nav.link-group',[
                        'group_title'       => __("Services"),
                        'group_links'       => [
                            [
                                'title'     => __("Service Types"),
                                'route'     => "admin.service.types.index",
                                'icon'      => "menu-icon las la-calendar-day",
                            ],
                            [
                                'title'     => __("Service Areas"),
                                'route'     => "admin.service.area.index",
                                'icon'      => "menu-icon las la-map-marker-alt",
                            ],
                            [
                                'title'     => __("Services"),
                                'route'     => "admin.service.index",
                                'icon'      => "menu-icon las la-water",
                            ],
                              [
                                'title'     => __("Coupons"),
                                'route'     => "admin.coupon.index",
                                'icon'      => "menu-icon las la-list",
                            ],
                        ]
                    ])
                 @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Booking Details"),
                    'group_links'       => [

                        [
                            'title'     => __("Service Booking"),
                            'route'     => "admin.service.booking.index",
                            'icon'      => "menu-icon las la-bookmark",
                        ],
                    ]
                ])
                {{-- Interface Panel --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Interface Panel"),
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => __("User Care"),
                                'icon'      => "menu-icon las la-user-edit",
                                'links'     => [
                                    [
                                        'title'     => __("Active Users"),
                                        'route'     => "admin.users.active",
                                    ],
                                    [
                                        'title'     => __("Email Unverified"),
                                        'route'     => "admin.users.email.unverified",
                                    ],
                                    // [
                                    //     'title'     => "KYC Unverified",
                                    //     'route'     => "admin.users.kyc.unverified",
                                    // ],
                                    [
                                        'title'     => __("All Users"),
                                        'route'     => "admin.users.index",
                                    ],
                                    [
                                        'title'     => __("Email To Users"),
                                        'route'     => "admin.users.email.users",
                                    ],
                                    [
                                        'title'     => __("Banned Users"),
                                        'route'     => "admin.users.banned",
                                    ]
                                ],
                            ],
                            [
                                'title'             => __("Admin Care"),
                                'icon'              => "menu-icon las la-user-shield",
                                'links'     => [
                                    [
                                        'title'     => __("All Admin"),
                                        'route'     => "admin.admins.index",
                                    ],
                                    [
                                        'title'     => __("Admin Role"),
                                        'route'     => "admin.admins.role.index",
                                    ],
                                    [
                                        'title'     => __("Role Permission"),
                                        'route'     => "admin.admins.role.permission.index",
                                    ],
                                    [
                                        'title'     => __("Email To Admin"),
                                        'route'     => "admin.admins.email.admins",
                                    ]
                                ],
                            ],
                        ],

                    ]
                ])

                {{-- Section Settings --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Settings"),
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => __("Web Settings"),
                                'icon'      => "menu-icon lab la-safari",
                                'links'     => [
                                    [
                                        'title'     => __("Basic Settings"),
                                        'route'     => "admin.web.settings.basic.settings",
                                    ],
                                    [
                                        'title'     => __("Image Assets"),
                                        'route'     => "admin.web.settings.image.assets",
                                    ],
                                    [
                                        'title'     => __("Setup SEO"),
                                        'route'     => "admin.web.settings.setup.seo",
                                    ]
                                ],
                            ],
                            [
                                'title'             => __("App Settings"),
                                'icon'              => "menu-icon las la-mobile",
                                'links'     => [
                                    [
                                        'title'     => __("Splash Screen"),
                                        'route'     => "admin.app.settings.splash.screen",
                                    ],
                                    [
                                        'title'     => __("Onboard Screen"),
                                        'route'     => "admin.app.settings.onboard.screens",
                                    ],
                                    [
                                        'title'     => __("App Urls"),
                                        'route'     => "admin.app.settings.urls",
                                    ],
                                ],
                            ],
                        ],
                    ]
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.languages.index',
                    'title'     => __("Languages"),
                    'icon'      => "menu-icon las la-language",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.system.maintenance.index',
                    'title'     => __("System Maintenance"),
                    'icon'      => "menu-icon las la-tools",
                ])
                {{-- Verification Center --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Verification Center"),
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => __("Setup Email"),
                                'icon'      => "menu-icon las la-envelope-open-text",
                                'links'     => [
                                    [
                                        'title'     => __("Email Method"),
                                        'route'     => "admin.setup.email.config",
                                    ],
                                    // [
                                    //     'title'     => "Default Template",
                                    //     'route'     => "admin.setup.email.template.default",
                                    // ]
                                ],
                            ]
                        ],

                    ]
                ])

                @if (admin_permission_by_name("admin.setup.sections.section"))
                    <li class="sidebar-menu-header">{{ __("Setup Web Content") }}</li>
                    @php
                        $current_url = URL::current();

                        $setup_section_childs  = [
                            setRoute('admin.setup.sections.section','banner'),
                            setRoute('admin.setup.sections.section','about'),
                            setRoute('admin.setup.sections.section','contact'),
                            setRoute('admin.setup.sections.section','security'),
                            setRoute('admin.setup.sections.section','service'),
                            setRoute('admin.setup.sections.section','statistics'),
                            setRoute('admin.setup.sections.section','why-choose-us'),
                            setRoute('admin.setup.sections.section','testimonial'),
                            setRoute('admin.setup.sections.section','category'),
                            setRoute('admin.setup.sections.section','announcement-section'),
                            setRoute('admin.setup.sections.section','faq'),
                            setRoute('admin.setup.sections.section','app'),
                            setRoute('admin.setup.sections.section','footer'),
                            setRoute('admin.setup.sections.section','auth'),

                        ];
                    @endphp

                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url,$setup_section_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-terminal"></i>
                            <span class="menu-title">{{ __("Setup Section") }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.setup.sections.section','banner') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','banner')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Banner Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','about') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','about')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("About Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','security') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','security')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Security Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','service') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','service')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Service Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','statistics') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','statistics')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Statistics Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','contact') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','contact')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Contact Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','why-choose-us') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','why-choose-us')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Why Choose Us Section") }}</span>
                                </a>
                                 <a href="{{ setRoute('admin.setup.sections.section','faq') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','faq')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("FAQ Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','app') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','app')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("App Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','testimonial') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','testimonial')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Testimonial Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','category') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','category')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Announcement  Category") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','announcement-section') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','announcement-section')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Announcement Section") }}</span>
                                </a>
                                    <a href="{{ setRoute('admin.setup.sections.section','footer') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','footer')) active @endif">
                                        <i class="menu-icon las la-ellipsis-h"></i>
                                        <span class="menu-title">{{ __("Footer Section") }}</span>
                                    </a>
                                    <a href="{{ setRoute('admin.setup.sections.section','auth') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','auth')) active @endif">
                                        <i class="menu-icon las la-ellipsis-h"></i>
                                        <span class="menu-title">{{ __("Auth Section") }}</span>
                                    </a>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.setup.pages.index',
                    'title'     => __("Setup Pages"),
                    'icon'      => "menu-icon las la-file-alt",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.useful.links.index',
                    'title'     => __("Useful Links"),
                    'icon'      => "menu-icon las la-link",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.extension.index',
                    'title'     => __("Extensions"),
                    'icon'      => "menu-icon las la-puzzle-piece",
                ])

                {{-- Notifications --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Notification"),
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => __("Push Notification"),
                                'icon'      => "menu-icon las la-bell",
                                'links'     => [
                                    [
                                        'title'     => __("Setup Notification"),
                                        'route'     => "admin.push.notification.config",
                                    ],
                                    [
                                        'title'     => __("Send Notification"),
                                        'route'     => "admin.push.notification.index",
                                    ]
                                ],
                            ]
                        ],

                    ]
                ])

                @php
                    $bonus_routes = [
                        'admin.cookie.index',
                        'admin.server.info.index',
                        'admin.cache.clear',
                    ];
                @endphp

                @if (admin_permission_by_name_array($bonus_routes))
                    <li class="sidebar-menu-header">{{ __("Bonus") }}</li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.cookie.index',
                    'title'     => __("GDPR Cookie"),
                    'icon'      => "menu-icon las la-cookie-bite",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.server.info.index',
                    'title'     => __("Server Info"),
                    'icon'      => "menu-icon las la-sitemap",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.cache.clear',
                    'title'     => __("Clear Cache"),
                    'icon'      => "menu-icon las la-broom",
                ])
            </ul>
        </div>
    </div>
</div>
