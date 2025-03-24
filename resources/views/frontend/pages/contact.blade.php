@extends('frontend.layouts.master')
@section('content')

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Contact
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div class="contact-section ptb-80">
        <div class="container">
            @if(@isset($contact->value))
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-8 col-lg-7 mb-30">
                    <div class="contact-form-area">
                        <div class="contact-header">
                            <h4 class="sub-title text--base pb-20">{{ @$contact->value->language->$lang->section_title ?? @$contact->value->language->$default->section_title  }}</span>
                                <h2 class="title">{{ @$contact->value->language->$lang->title ??@$contact->value->language->$default->title }}</h2>
                        </div>
                        <form class="contact-form pt-20" action="{{ setRoute('frontend.contact.message.store') }}" method="POST">
                            @csrf
                            <div class="row justify-content-center mb-10-none">
                                <div class="col-xl-6 col-lg-6 col-md-12 form-group">
                                    <label>{{ __("Name") }}<span>*</span></label>
                                    <input type="text" name="name" class="form--control" placeholder="{{ __("Enter Name") }}...">
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 form-group">
                                    <label>{{ __("Email") }}<span>*</span></label>
                                    <input type="email" name="email" class="form--control" placeholder="{{ __("Enter Email") }}...">
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    <label>{{ __("Message") }}<span>*</span></label>
                                    <textarea class="form--control" name="message" placeholder="{{ __("Write Here") }}..."></textarea>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <button type="submit" class="btn--base mt-20">{{ __("Send Message") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 mb-30">
                    <div class="contact-information">
                        <h3 class="title">{{ @$contact->value->language->$lang->description_title ?? @$contact->value->language->$default->description_title }}</h3>
                        <p>{{ @$contact->value->language->$lang->description ?? @$contact->value->language->$default->description }}</p>
                    </div>
                    <div class="contact-widget-box">
                        <div class="contact-widget-item-wrapper">
                            <div class="contact-widget-item">
                                <div class="contact-widget-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                        class="fill-jacarta-400">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M9.366 10.682a10.556 10.556 0 0 0 3.952 3.952l.884-1.238a1 1 0 0 1 1.294-.296 11.422 11.422 0 0 0 4.583 1.364 1 1 0 0 1 .921.997v4.462a1 1 0 0 1-.898.995c-.53.055-1.064.082-1.602.082C9.94 21 3 14.06 3 5.5c0-.538.027-1.072.082-1.602A1 1 0 0 1 4.077 3h4.462a1 1 0 0 1 .997.921A11.422 11.422 0 0 0 10.9 8.504a1 1 0 0 1-.296 1.294l-1.238.884zm-2.522-.657l1.9-1.357A13.41 13.41 0 0 1 7.647 5H5.01c-.006.166-.009.333-.009.5C5 12.956 11.044 19 18.5 19c.167 0 .334-.003.5-.01v-2.637a13.41 13.41 0 0 1-3.668-1.097l-1.357 1.9a12.442 12.442 0 0 1-1.588-.75l-.058-.033a12.556 12.556 0 0 1-4.702-4.702l-.033-.058a12.442 12.442 0 0 1-.75-1.588z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="contact-widget-content">
                                    <h4 class="title">{{ @$contact->value->language->$lang->call_title ?? @$contact->value->language->$default->call_title }}</h4>
                                    <span class="sub-title"><a href="javascript:void(0)">{{ @$contact->value->language->$lang->mobile ?? @$contact->value->language->$default->mobile }}</a></span>
                                </div>
                            </div>
                            <div class="contact-widget-item">
                                <div class="contact-widget-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                        class="fill-jacarta-400">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="contact-widget-content">
                                    <h4 class="title">{{ @$contact->value->language->$lang->location_title ??@$contact->value->language->$default->location_title  }}</h4>
                                    <span class="sub-title">{{ @$contact->value->language->$lang->location ??  @$contact->value->language->$default->location }}</span>
                                </div>
                            </div>
                            <div class="contact-widget-item">
                                <div class="contact-widget-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                        class="fill-jacarta-400">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M2.243 6.854L11.49 1.31a1 1 0 0 1 1.029 0l9.238 5.545a.5.5 0 0 1 .243.429V20a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.283a.5.5 0 0 1 .243-.429zM4 8.133V19h16V8.132l-7.996-4.8L4 8.132zm8.06 5.565l5.296-4.463 1.288 1.53-6.57 5.537-6.71-5.53 1.272-1.544 5.424 4.47z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="contact-widget-content">
                                    <h4 class="title">{{ @$contact->value->language->$lang->email_title ?? @$contact->value->language->$default->email_title }}</h4>
                                    <span class="sub-title"><a href="javascript:void(0)">{{ @$contact->value->language->$lang->email_address ?? @$contact->value->language->$default->email_address }}</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Contact
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!-- app section -->
@include('frontend.sections.app-section')
@endsection
