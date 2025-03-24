@php
    $lang = selectedLang();
    $default =  App\Constants\LanguageConst::NOT_REMOVABLE;
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

<section class="forgot-password  ptb-80">
    <div class="container">
        @if(@isset($auth->value))
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-10">
                <div class="forgot-password-area">
                    <div class="account-wrapper">
                        <div class="account-logo text-center">
                            <a href="{{setRoute('frontend.index')}}" class="site-logo">
                                <img src="{{ get_logo($basic_settings)}}" alt="logo">
                            </a>
                        </div>
                        <div class="forgot-password-content">
                            <h3 class="title">{{  @$auth->value->language->$lang->forgot_heading ??  @$auth->value->language->$default->forgot_heading }}</h3>
                            <p>{{  @$auth->value->language->$lang->forgot_sub_heading ??@$auth->value->language->$default->forgot_sub_heading  }}</p>
                        </div>
                        <form class="account-form pt-30" action="{{ setRoute('user.password.forgot.send.code') }}" method="POST">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group pb-20">
                                    <input type="email" required class="form-control form--control" name="credentials" placeholder="{{ __("Email") }}" spellcheck="false" data-ms-editor="true">
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="btn--base w-100"">{{ __("Send OTP") }}</button>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <div class="account-item">
                                        <label>{{ __("Back to") }} <a href="{{ setRoute('frontend.index') }}" class="text--base">{{ __("Home") }}</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@include('partials.footer-asset')
@include('admin.partials.notify')
</body>
</html>
