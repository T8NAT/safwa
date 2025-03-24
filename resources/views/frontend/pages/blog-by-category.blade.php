@extends('frontend.layouts.master')

@php
    $lang = selectedLang();
@endphp

@section('content')

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="blog-section ptb-120">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-4 col-lg-5 col-md-6 mb-30">
                <div class="blog-sidebar">
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">{{ __("Categories") }}</h4>
                        <div class="category-widget-box">
                            <ul class="category-list">
                                @foreach ($categories ?? [] as $cat)
                                @php
                                     $announcementCount = App\Models\Admin\Announcement::active()->where('category_id',$cat->id)->count();
                                @endphp
                                    @if( $announcementCount > 0)
                                    <li><a href="{{ route('frontend.blog.by.category',$cat->id) }}"> {{ @$cat->name}}<span>{{ @$announcementCount }}</span></a></li>
                                    @else
                                    <li><a href="javascript:void(0)"> {{ @$cat->name}}<span>{{ @$announcementCount }}</span></a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="widget-box mb-30">
                        <h4 class="widget-title">{{ _("Recent Posts") }}</h4>
                        <div class="popular-widget-box">
                            @foreach ($recentPost as $post)
                            <div class="single-popular-item d-flex flex-wrap align-items-center">
                                <div class="popular-item-thumb">
                                    <a href=" "><img src="{{ get_image(@$post->image,'announcement') }}" alt="blog"></a>
                                </div>
                                <div class="popular-item-content">
                                    <span class="date"> {{@$post->created_at->format('d F, Y') }}</span>
                                    <h5 class="title"><a href="{{route('frontend.blog.details',[$post->id, @$post->slug])}}">{{ @$post->name->language->$lang->name ?? @$post->name->language->$default->name }}</a></h5>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="widget-box">
                        <h4 class="widget-title">{{ __("Tags") }}</h4>
                        <div class="tag-widget-box">
                            <ul class="tag-list">
                                @foreach ($allTags as $tag)
                                <li><a href="javascript:void(0)">{{ @$tag }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-6 mb-30">
                <div class="row justify-content-center mb-30-none">
                    @foreach ($announcements??[] as $announcement)
                    <div class="col-xl-12 col-lg-12 mb-30">
                        <div class="blog-item">
                            <div class="blog-thumb blog-category-img">
                                <img src="{{ get_image(@$announcement->image,'announcement') }}" alt="blog">
                            </div>
                            <div class="blog-content">
                                <span class="sub-title"><i class="las la-calendar"></i>{{@$announcement->created_at->format('d F, Y') }}</span>
                                <h3 class="title"><a href="{{ setRoute('frontend.blog.details',[@$announcement->id,@$announcement->slug]) }}">{{ @$announcement->name->language->$lang->name ?? @$announcement->name->language->$default->name }}</a></h3>
                                <p> {{textLength(strip_tags(@$announcement->details->language->$lang->details ?? @$announcement->details->language->$default->details,120))}}</p>
                                <div class="blog-btn">
                                    <a href="{{ setRoute('frontend.blog.details',[$announcement->id,$announcement->slug]) }}">{{ __("Read More") }} <i class="las la-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                 @endforeach
                </div>
            </div>

        </div>
        <nav>
            <ul class="pagination">
                {{ get_paginate($announcements) }}
            </ul>
        </nav>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection


@push("script")

@endpush
