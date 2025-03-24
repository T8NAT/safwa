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
    ], 'active' => __("Contact Messages")])
@endsection

@section('content')
    <div class="table-area">
            <div class="table-header">
                <h5 class="title">{{ __("Messages") }}</h5>
            </div>
            <div class="table-responsive">
                @include('admin.sections.contact.messages-table',[
                    'data'  => $messages
                ])
        </div>
        {{ get_paginate($messages) }}
    </div>

    {{-- Message Reply Modal --}}
    @include('admin.sections.contact.reply-message')
     {{-- Message Show Modal --}}
    @include('admin.sections.contact.message-details')


@endsection

@push('script')
    <script>
        $(".delete-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute =  "{{ setRoute('admin.contact.delete') }}";
            var target = oldData.id;
            console.log(oldData);
            var message = `{{ __("Are you sure to delete this item?") }}`;

            openDeleteModal(actionRoute,target,message);
        });
    </script>
@endpush
