@extends('frontend.layouts.master')
@section('content')

    <!-- Blog section  -->
    <section class="blog-section ptb-80">
        <div class="container">
            @if(@isset($announcement->value))
            <div class="section-header-title">
                <div class="section-title pb-20">
                    <h4 class="sub-title text--base">{{@$announcement->value->language->$lang->section_title ?? @$announcement->value->language->$default->section_title }}</h4>
                </div>
                <div class="blog-title">
                    <h2 class="title">{{@$announcement->value->language->$lang->title ?? @$announcement->value->language->$default->title }}</h2>
                </div>
            </div>
            <div class="blog-area pt-40">
                @foreach ($latestAnnouncement??[] as $key => $announcement)
                <div class="blog-item mb-30">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="blog-img">
                                <img src="{{ get_image(@$announcement->image,'announcement') }}" alt="blog">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <div class="blog-content">
                                <h3 class="title">{{ @$announcement->name->language->$lang->name ?? @$announcement->name->language->$default->name }}</h3>
                                <p>{{textLength(strip_tags(@$announcement->details->language->$lang->details ?? @$announcement->details->language->$default->details,120))}}.
                                </p>
                                <div class="blog-btn">
                                    <a href="{{ setRoute('frontend.blog.details',[@$announcement->id,@$announcement->slug]) }}" class="btn--base btn">{{ __("Blog Details") }}
                                    </a>
                                </div>
                            </div>
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
