@extends('frontend.layouts.master')
@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    service Section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section class="service-section ptb-80">
        <div class="container">
            @if(@isset($service->value))
            <div class="row">
                <div class="col-lg-8">
                    <div class="service-content">
                        <div class="section-title pb-20">
                            <h4 class="sub-title text--base">{{ @$service->value->language->$lang->section_title ?? @$service->value->language->$default->section_title }}</h4>
                        </div>
                        <div class="service-title pb-20">
                            <h2 class="title">{{ @$service->value->language->$lang->title ??  @$service->value->language->$default->title  }}</h2>
                        </div>
                        <div class="service-paragraph pb-40">
                            <p>{{ @$service->value->language->$lang->description ??@$service->value->language->$default->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($service->value->items ?? []  as $key => $item )
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 pb-20">
                    <div class="service-area">
                        <div class="icon">
                            <i class="{{ @$item->language->$lang->item_section_icon ??  @$item->language->$default->item_section_icon }}"></i>
                        </div>
                        <div class="area-content">
                            <h4 class="title">{{ @$item->language->$lang->item_title ?? @$item->language->$default->item_title }}</h4>
                            <p>{{ @$item->language->$lang->item_description ??@$item->language->$default->item_description  }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>


    <!-- app section -->
@include('frontend.sections.app-section')
@endsection
