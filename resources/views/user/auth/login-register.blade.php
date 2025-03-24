<div class="account-section">
    @if(@isset($auth->value))
    <div class="account-bg"></div>
    <div class="account-area change-form">
        <div class="row">
            <div class="col-lg-6">
                    <div class="account-section-img">
                        <img src="{{ get_image(@$auth->value->login_image, 'site-section') }}" alt="auth">
                    </div>
            </div>
            <div class="col-lg-6">
                <div class="account-close"></div>
                <div class="account-form-area">
                    <h3 class="title">{{ @$auth->value->language->$lang->login_heading ?? @$auth->value->language->$default->login_heading }}</h3>
                    <p>{{ @$auth->value->language->$lang->login_sub_heading ??@$auth->value->language->$default->login_sub_heading  }}</p>
                    <form action="{{ setRoute('user.login.submit') }}" class="account-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <input type="email"  required class="form-control form--control" name="credentials" placeholder="{{ __("Enter Email") }}">
                            </div>
                            <div class="col-lg-12 form-group show_hide_password">
                                <input type="password" required class="form-control form--control" name="password" placeholder="{{ __("Enter Password") }}">
                                <a href="#0" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="forgot-item text-end">
                                    <label><a href="{{ setRoute('user.password.forgot') }}" class="text--base">{{ __("Forgot Password") }}?</a></label>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __("Login Now") }}</button>
                            </div>
                            @if($basic_settings->user_registration)
                            <div class="col-lg-12 text-center">
                                <div class="account-item">
                                    <label>{{ __("Don't Have An Account") }}? <a href="javascript:void(0)" class="account-control-btn">{{ __("Register Now") }}</a></label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="account-area">
        <div class="account-close"></div>
        <div class="row">
            <div class="col-lg-6">
                <div class="account-section-img">
                    <img src="{{ get_image(@$auth->value->register_image, 'site-section') }}" alt="auth">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="account-form-area">
                    <h3 class="title">{{  @$auth->value->language->$lang->register_heading ?? @$auth->value->language->$default->register_heading }}</h3>
                    <p>{{ @$auth->value->language->$lang->register_sub_heading ?? @$auth->value->language->$default->register_sub_heading }}</p>
                    <form action="{{ setRoute('user.register.submit') }}" method="POST" class="account-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 form-group">
                                <input type="text" class="form-control form--control" name="firstname" placeholder="{{ __("First Name") }}">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <input type="text" class="form-control form--control" name="lastname" placeholder="{{ __("Last Name") }}">
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="email" class="form-control form--control" name="email" placeholder="{{ __("Email") }}">
                            </div>
                            <div class="col-lg-12 form-group">
                                <select class="form--control select2-auto-tokenize country-select w-100" data-placeholder="{{ __("Select Country") }}" name="country"></select>
                                <input class="phone-code" type="hidden" name="mobile_code" value="" />
                            </div>
                            <div class="col-lg-12 form-group show_hide_password">
                                <input type="password" class="form-control form--control" name="password" placeholder="{{ __("Password") }}">
                                <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            </div>
                            @if (@$basic_settings->agree_policy == 1)
                            @php
                                $type =  Str::slug(App\Constants\GlobalConst::USEFUL_LINKS);
                                $policy = App\Models\Admin\SetupPage::orderBy('id')->where('type', $type)->where('status',1)->where('slug','privacy-policy')->first();
                            @endphp
                            <div class="col-lg-12 form-group">
                                <div class="custom-check-group">
                                    <input type="checkbox" id="level-1" name="agree">
                                    <label for="level-1">{{ __("I have agreed with") }}
                                        @if ($policy != null) <a href="{{ setRoute('frontend.useful.link',$policy->slug) }}">{{ __("Terms Of Use") }} &amp; {{ __("Privacy     Policy") }}</a></label>
                                        @endif
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __("Register Now") }}</button>
                            </div>
                            <div class="col-lg-12 text-center">
                                <div class="account-item">
                                    <label>{{ __("Already Have An Account?") }} <a href="javascript:void(0)" class="account-control-btn">{{ __("Login Now") }}</a></label>
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

@push('script')

    <script>
        getAllCountries("{{ setRoute('global.countries') }}",$(".country-select"));
        $(document).ready(function(){
            $(".country-select").select2();
        });
        $(document).on("change","select[name=country]",function(){
            var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
            placePhoneCode(phoneCode);
        });
    </script>

@endpush

