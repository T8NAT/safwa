@php
$default_lang_code = language_const()::NOT_REMOVABLE;
$system_default_lang = get_default_language_code();
$languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
<link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.min.css') }}">
<style>
    .fileholder {
        min-height: 374px !important;
    }

    .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
    .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
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
'name' => __("Dashboard"),
'url' => setRoute("admin.dashboard"),
]
], 'active' => __("Statistics Section")])
@endsection

@section('content')
<div class="custom-card">
    <div class="card-header">
        <h6 class="title">{{ __($page_title) }}</h6>
    </div>
    <div class="card-body">
        <form class="card-form" action="{{ setRoute('admin.setup.sections.section.update',$slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center mb-10-none">
                <div class="col-xl-4 col-lg-4 form-group">
                    @include('admin.components.form.input-file',[
                        'label'             => __("Image").':',
                        'name'              => "image",
                        'class'             => "file-holder",
                        'old_files_path'    => files_asset_path("site-section"),
                        'old_files'         => $data->value->image ?? "",
                    ])
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    @include('admin.components.button.form-btn',[
                        'class'         => "w-100 btn-loading",
                        'text'          => __("Submit"),
                        'permission'    => "admin.setup.sections.section.update"
                    ])
                </div>
            </div>
        </form>
    </div>
</div>
<div class="table-area mt-15">
    <div class="table-wrapper">
        <div class="table-header justify-content-end">
            <div class="table-btn-area">
                <a href="#statistics-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add Statistics Item")
                    }}</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>{{ __("Heading") }}</th>
                        <th>{{ __("Sub Heading") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data->value->items ?? [] as $key => $item)
                    <tr data-item="{{ json_encode($item) }}">
                        <td>{{ $item->language->$system_default_lang->heading?? "" }}</td>
                        <td>{{ $item->language->$system_default_lang->sub_heading?? "" }}</td>
                        <td>
                            <button class="btn btn--base edit-modal-button"><i class="las la-pencil-alt"></i></button>
                            <button class="btn btn--base btn--danger delete-modal-button"><i
                                    class="las la-trash-alt"></i></button>
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

@include('admin.components.modals.site-section.add-statistics-item')

{{-- statistics Edit Modal --}}
<div id="statistics-edit" class="mfp-hide large">
    <div class="modal-data">
        <div class="modal-header px-0">
            <h5 class="modal-title">{{ __("Edit Statistics Item") }}</h5>
        </div>
        <div class="modal-form-data">
            <form class="modal-form" method="POST"
                action="{{ setRoute('admin.setup.sections.section.item.update',$slug) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="target" value="{{ old('target') }}">
                <div class="row mb-10-none mt-3">
                    <div class="language-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach ($languages as $item)
                                <button class="nav-link @if (get_default_language_code() == $item->code) active @endif"
                                    id="edit-modal-{{$item->name}}-tab" data-bs-toggle="tab"
                                    data-bs-target="#edit-modal-{{$item->name}}" type="button" role="tab"
                                    aria-controls="edit-modal-{{ $item->name }}" aria-selected="true">{{ $item->name
                                    }}</button>
                                @endforeach

                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($languages as $item)
                            @php
                            $lang_code = $item->code;
                            @endphp
                            <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif"
                                id="edit-modal-{{ $item->name }}" role="tabpanel"
                                aria-labelledby="edit-modal-{{$item->name}}-tab">
                                <div class="form-group">
                                    @include('admin.components.form.input',[
                                    'label' => __("Heading").'*',
                                    'type'  => "number",
                                    'name'  => $lang_code . "_heading_edit",
                                    'value' => old($lang_code . "_heading_edit",$data->value->language->$lang_code->heading
                                    ?? "")
                                    ])
                                </div>
                                <div class="form-group">
                                    @include('admin.components.form.input',[
                                    'label' => __("Sub Heading").'*',
                                    'name' => $lang_code . "_sub_heading_edit",
                                    'value' => old($lang_code .
                                    "_sub_heading_edit",$data->value->language->$lang_code->sub_heading ?? "")
                                    ])
                                </div>

                                <div class="form-group">
                                    @include('admin.components.form.input',[
                                        'label'     => __("Section Icon").'*',
                                        'name'      => $lang_code . "_section_icon_edit",
                                        'value'     => old($lang_code . "_section_icon_edit", $data->value->language->$lang_code->section_icon ?? ""),
                                        'class'     => "form--control icp icp-auto iconpicker-element iconpicker-input",
                                    ])
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Update") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('public/backend/js/fontawesome-iconpicker.js') }}"></script>
<script>
    $(".input-field-generator .add-row-btn").click(function(){
            // alert();
            setTimeout(() => {
                $('.icp-auto').iconpicker();
            }, 500);
        });
    // icon picker
        $('.icp-auto').iconpicker();
</script>
<script>
    openModalWhenError("statistics-add","#statistics-add");
        openModalWhenError("statistics-edit","#statistics-edit");

        var default_language = "{{ $default_lang_code }}";
        var system_default_language = "{{ $system_default_lang }}";
        var languages = "{{ $languages_for_js_use }}";
        languages = JSON.parse(languages.replace(/&quot;/g,'"'));

        $(".edit-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
            var editModal = $("#statistics-edit");

            console.log(oldData);

            editModal.find("form").first().find("input[name=target]").val(oldData.id);

            $.each(languages,function(index,item) {
                editModal.find("input[name="+item.code+"_heading_edit]").val(oldData.language[item.code]?.heading);
                editModal.find("input[name="+item.code+"_sub_heading_edit]").val(oldData.language[item.code]?.sub_heading);
                editModal.find("input[name="+item.code+"_section_icon_edit]").val(oldData.language[item.code]?.section_icon);
            });
            openModalBySelector("#statistics-edit");
        });

        $(".delete-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute =  "{{ setRoute('admin.setup.sections.section.item.delete',$slug) }}";
            var target = oldData.id;
            var message = `{{ __("Are you sure to delete this item?") }}`;

            openDeleteModal(actionRoute,target,message);
        });
</script>
@endpush
