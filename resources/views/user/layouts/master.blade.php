<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $basic_settings->site_name }} {{ $page_title ?? '' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;0,9..40,800;0,9..40,900;0,9..40,1000;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700;1,9..40,800;1,9..40,900&family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    @include('partials.header-asset')
    @stack("css")
</head>
<body class="{{ get_default_language_dir() }}">
    @include('frontend.partials.preloader')
    @include('frontend.partials.body-overlay')
    <div class="page-wrapper">
        @include('user.partials.side-nav')
        <div class="main-wrapper">
            <div class="main-body-wrapper">
                @include('user.partials.top-nav')

                @yield('content')
            </div>
        </div>
    </div>
    @include('partials.footer-asset')
    @include('admin.partials.notify')
    @stack("script")

</body>
</html>
