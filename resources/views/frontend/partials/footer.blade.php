<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Footer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@if(@isset($footer->value))
    <footer class="footer-section">
        <div class="container mx-auto">
            <div class="footer-content pt-60">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="footer-widget">
                            <div class="footer-text">
                                <img src="{{ get_logo($basic_settings) }}"data-white_img="{{ get_logo($basic_settings,'white') }}" alt="site-logo">
                                <p>{{@$footer->value->language->$lang->short_description ?? @$footer->value->language->$default->short_description }}</p>
                            </div>
                            <div class="footer-social-icon">
                                <span>{{@$footer->value->language->$lang->icon_title ?? @$footer->value->language->$default->icon_title }}</span>
                                    @foreach($footer->value->items ?? [] as $key => $item)
                                        <a href="{{@$item->item_link }}" target="_blank"><i class="{{@$item->item_social_icon }} icon-size"> </i></a>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>{{ __('Useful Links') }}</h3>
                            </div>
                            @foreach ($policies ?? [] as $key=> $data)
                            <ul>
                                <li>

                                    @if ($data != null)<a href="{{ route('frontend.useful.link',$data->slug) }}">
                                        {{ $data->title->language->$lang->title ?? $data->title->language->$default->title }}</a></li>
                                    @endif
                            </ul>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>{{ __("Subscribe") }}</h3>
                            </div>
                            <div class="footer-text mb-25">
                                <p>{{@$footer->value->language->$lang->subscribe_title ?? @$footer->value->language->$default->subscribe_title }}</p>
                            </div>
                            <div class="subscribe-form">
                                <form action="{{ setRoute('frontend.subscribers.store') }}" method="POST">
                                    @csrf
                                    <input type="text" name="email" class="form--control" placeholder="{{__("Email Address")}}">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-lg-left">
                        <div class="copyright-text">
                            @php
                                $change_text = $footer->value->language->$lang->footer_text ?? @$footer->value->language->$default->footer_text;
                                $year = date('Y');
                                $dynamic_text = str_replace("{date}", $year, $change_text);
                            @endphp
                            <p>{{ $dynamic_text }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endif
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Footer
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    @push('script')
    @endpush
