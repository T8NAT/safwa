@php
    $lang = selectedLang();
@endphp
<!DOCTYPE html>
<html lang="{{ get_default_language_code() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($page_title) ? __($page_title) : __("Public")) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;0,9..40,800;0,9..40,900;0,9..40,1000;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700;1,9..40,800;1,9..40,900&family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    @include('partials.header-asset')
    @stack('css')
</head>
<body class="{{ get_default_language_dir() }}">
    @include('frontend.partials.preloader')
    @include('frontend.partials.body-overlay')

<div class="new-password ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="new-password-area">
                    <div class="account-wrapper">
                        <span class="account-cross-btn"></span>
                        <div class="account-logo text-center">
                            <a href="{{setRoute('frontend.index')}}" class="site-logo">
                                <img src="{{ get_logo($basic_settings)}}" alt="logo">
                            </a>
                        </div>
                        <form class="account-form" action="{{ setRoute('user.password.reset',$token) }}" method="POST">
                            @csrf
                            <div class="row ml-b-20">
                                <label>{{ __("Enter New Password") }}</label>
                                <div class="col-lg-12 form-group show_hide_password">
                                    <input type="password" name="password" class="form-control form--control"  placeholder="{{ __("Enter New Password") }}">
                                    <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <label>{{ __("Confirm Password") }}</label>
                                <div class="col-lg-12 form-group show_hide_password-2">
                                    <input type="password" name="password_confirmation" class="form-control form--control"  placeholder="{{ __("Confirm Password") }}">
                                    <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-lg-12 form-group text-center pt-3">
                                    <button type="submit" class="btn--base w-100">{{ __("Confirm") }}</button>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <div class="account-item">
                                        <label>{{ __("Back to") }} <a href="{{ setRoute('frontend.index') }}" class="text--base" data-block="login">{{__("Home")}}</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer-asset')
@include('admin.partials.notify')

</body>
</html>
