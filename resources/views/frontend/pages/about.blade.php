@extends('frontend.layouts.master')
@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    About Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="about-section ptb-80">
    <div class="container">
        @if(@isset($about->value))
        <div class="row">
            <div class="col-xl-6 col-lg-12 pb-20">
                <div class="about-img">
                    <img src="{{ get_image(@$about->value->image, 'site-section') }}" alt="about">
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="about-content-area">
                    <div class="section-title pb-20">
                        <h4 class="sub-title text--base">{{@$about->value->language->$lang->section_title ?? @$about->value->language->$default->section_title }}</h4>
                    </div>
                    <div class="about-title pb-20">
                        <h2 class="title">{{@$about->value->language->$lang->title ?? @$about->value->language->$default->title }}</h2>
                    </div>
                    <div class="about-paragraph">
                         <p>{!!@$about->value->language->$lang->description ??@$about->value->language->$default->description !!}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Faq
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.sections.faq-section')

<!-- app section -->
@include('frontend.sections.app-section')
@endsection
