@php
    $type = App\Constants\GlobalConst::SETUP_PAGE;
    $menues = DB::table('setup_pages')
            ->where('status', 1)
            ->where('type', Str::slug($type))
            ->get();
    $current_url = URL::current();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<header class="header-section position-relative">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container custom-container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{setRoute('frontend.index')}}"><img src="{{ get_logo($basic_settings) }}" alt="site-logo"></a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ms-auto">
                                @foreach ($menues as $item)
                                    @php
                                        $title = json_decode($item->title);
                                    @endphp
                                    <li><a href="{{ url($item->url) }}" class="@if ($current_url == url($item->url)) active @endif">{{ __($title->title) }}</a></li>
                                @endforeach
                            </ul>
                            <div class="language-select">
                                <select class="nice-select" name="lang_switcher" id="">
                                    @foreach($__languages as $item)
                                    <option value="{{$item->code}}" @if (get_default_language_code() == $item->code) selected  @endif>{{$item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="header-action">
                                @auth
                                    <a href="{{ setRoute('user.profile.index') }}" class="btn--base">
                                        {{ __('Dashboard') }}
                                    </a>
                                @else
                                    <a href="javascript:void(0)" class="btn--base header-account-btn">
                                        {{ __('Login Now') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@push('script')
    <script>
        $("select[name=lang_switcher]").change(function(){
            var selected_value = $(this).val();
            var submitForm = `<form action="{{ setRoute('frontend.language.switch') }}" id="local_submit" method="POST"> @csrf <input type="hidden" name="target" value="${$(this).val()}" ></form>`;
            $("body").append(submitForm);
            $("#local_submit").submit();
        });
    </script>
@endpush
