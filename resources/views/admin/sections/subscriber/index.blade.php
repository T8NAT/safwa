@extends('admin.layouts.master')

@push('css')
    <style>
        .fileholder {
            min-height: 194px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 150px !important;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Subscribers")])
@endsection

@section('content')
    <div class="table-area">
            <div class="table-header">
                <h5 class="title">{{ __("Subscribers") }}</h5>
            </div>
            <div class="table-responsive">
                @include('admin.sections.subscriber.subscribers-table',[
                    'data'  => $subscribers
                ])
        </div>
        {{ get_paginate($subscribers) }}
    </div>

    {{-- Message Reply Modal --}}
    @include('admin.sections.subscriber.reply-message')

@endsection

@push('script')
    <script>
    </script>
@endpush
