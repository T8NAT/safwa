@php
    $default_lang_code = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.css') }}">
    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
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
    ], 'active' => __("Setup Section")])
@endsection

@section('content')

    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header">
                <h6 class="title">{{ __($page_title) }}</h6>
                <div class="table-btn-area">
                    <a href="#useful-link-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Page") }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Page") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item) }}">
                                <td>
                                    {{ $item->title->language->$system_default_lang->title ?? "" }}
                                </td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'          => 'campaign_status',
                                        'value'         => $item->status,
                                        'options'       => [__('Enable') => 1,__('Disable') => 0],
                                        'onload'        => true,
                                        'data_target'   => $item->id,
                                        'permission'    => "admin.campaigns.items.status.update",
                                    ])
                                </td>
                                <td>
                                    <a href="{{ setRoute('admin.useful.links.edit', $item->id) }}" class="btn btn--base"><i
                                        class="las la-pencil-alt"></i></a>
                                    @if($item->slug !== 'privacy-policy')
                                        <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 4])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.components.modals.site-section.useful-link-add')

@endsection

@push('script')

    <script>
        openModalWhenError("useful-link-add","#useful-link-add");
        openModalWhenError("useful-link-edit","#useful-link-edit");

        var default_language = "{{ $default_lang_code }}";
        var system_default_language = "{{ $system_default_lang }}";
        var languages = "{{ $languages_for_js_use }}";
        languages = JSON.parse(languages.replace(/&quot;/g,'"'));

        $(".delete-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute =  "{{ setRoute('admin.useful.links.delete') }}";
            var target = oldData.id;

            var message = `{{ __("Are you sure to delete this item?") }}`;

            openDeleteModal(actionRoute,target,message);
        });

        // Switcher
        switcherAjax("{{ setRoute('admin.useful.links.status.update') }}");

    </script>
@endpush
