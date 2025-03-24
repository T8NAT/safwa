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
            <div class="table-header justify-content-end">
                <div class="table-btn-area">
                    <a href="#howWeDo-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i> {{ __("Add How We Do Item") }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Titile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->value->items ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item) }}">
                                <td>
                                    <ul class="user-list">
                                        <li><img src="{{ get_image($item->image ?? "","site-section") }}" alt="product"></li>
                                    </ul>
                                </td>
                                <td>{{ $item->language->$system_default_lang->item_title ?? "" }}</td>
                                <td>
                                    <button class="btn btn--base edit-modal-button"><i class="las la-pencil-alt"></i></button>
                                    <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
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

    @include('admin.components.modals.site-section.add-how-we-do-item')

    {{-- how we do Edit Modal --}}
    <div id="howWeDo-edit" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Edit How We Do") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.setup.sections.section.item.update',$slug) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="target" value="{{ old('target') }}">
                    <div class="row mb-10-none mt-3">
                        <div class="language-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link @if (get_default_language_code() == language_const()::NOT_REMOVABLE) active @endif" id="edit-modal-english-tab" data-bs-toggle="tab" data-bs-target="#edit-modal-english" type="button" role="tab" aria-controls="edit-modal-english" aria-selected="false">English</button>
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="edit-modal-{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#edit-modal-{{$item->name}}" type="button" role="tab" aria-controls="edit-modal-{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane @if (get_default_language_code() == language_const()::NOT_REMOVABLE) fade show active @endif" id="edit-modal-english" role="tabpanel" aria-labelledby="edit-modal-english-tab">
                                    <div class="form-group">
                                        @include('admin.components.form.input',[
                                            'label'     => "Item Title*",
                                            'name'      => $default_lang_code . "_item_title_edit",
                                            'value'     => old($default_lang_code . "_item_title_edit",
                                            $data->value->language->$default_lang_code->item_title ?? "")
                                        ])
                                    </div>
                                    <div class="form-group">
                                        @include('admin.components.form.input',[
                                            'label'     => "Item Description*",
                                            'name'      => $default_lang_code . "_item_description_edit",
                                            'value'     => old($default_lang_code . "_item_description_edit",$data->value->language->$default_lang_code->item_description ?? "")
                                        ])
                                    </div>
                                    <div class="form-group">
                                        @include('admin.components.form.input',[
                                            'label'     => "Item Social Icon*",
                                            'name'      => $default_lang_code . "_item_social_icon_edit",
                                            'value'     => old($default_lang_code . "_item_social_icon_edit",
                                            $data->value->language->$default_lang_code->item_social_icon ?? ""),
                                            'class'     => "form--control icp icp-auto iconpicker-element iconpicker-input",
                                        ])
                                    </div>
                                </div>

                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp
                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="edit-modal-{{ $item->name }}" role="tabpanel" aria-labelledby="edit-modal-{{$item->name}}-tab">
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'     => "Item Title*",
                                                'name'      => $lang_code . "_item_title_edit",
                                                'value'     => old($lang_code . "_item_title_edit",
                                                $data->value->language->$lang_code->item_title ?? "")
                                            ])
                                        </div>
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'     => "Item Description*",
                                                'name'      => $lang_code . "_item_description_edit",
                                                'value'     => old($lang_code . "_item_description_edit",
                                                $data->value->language->$lang_code->item_description ?? "")
                                            ])
                                        </div>
                                        <div class="form-group">
                                            @include('admin.components.form.input',[
                                                'label'     => "Item Social Icon*",
                                                'name'      => $lang_code . "_item_social_icon_edit",
                                                'value'     => old($lang_code . "_item_social_icon_edit",
                                                $data->value->language->$lang_code->item_social_icon ?? ""),
                                                'class'     => "form--control icp icp-auto iconpicker-element iconpicker-input",
                                            ])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input-file',[
                                'label'             => "Image",
                                'name'              => "image",
                                'class'             => "file-holder",
                                'old_files_path'    => files_asset_path("site-section"),
                                'old_files'         => old("old_image"),
                            ])
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
        openModalWhenError("howWeDo-add","#howWeDo-add");
        openModalWhenError("howWeDo-edit","#howWeDo-edit");

        var default_language = "{{ $default_lang_code }}";
        var system_default_language = "{{ $system_default_lang }}";
        var languages = "{{ $languages_for_js_use }}";
        languages = JSON.parse(languages.replace(/&quot;/g,'"'));

        $(".edit-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
            var editModal = $("#howWeDo-edit");

            console.log(oldData);

            editModal.find("form").first().find("input[name=target]").val(oldData.id);
            editModal.find("input[name="+default_language+"_item_title_edit]").val(oldData.language[default_language].item_title);
            editModal.find("input[name="+default_language+"_item_description_edit]").val(oldData.language[default_language].item_description);
            editModal.find("input[name="+default_language+"_item_social_icon_edit]").val(oldData.language[default_language].item_social_icon);


            $.each(languages,function(index,item) {
                editModal.find("input[name="+item.code+"_item_title_edit]").val(oldData.language[item.code].item_title);
                editModal.find("input[name="+item.code+"_item_description_edit]").val(oldData.language[item.code].item_description);
                editModal.find("input[name="+item.code+"_item_social_icon_edit]").val(oldData.language[item.code].item_social_icon);
            });
            editModal.find("input[name=image]").attr("data-preview-name",oldData.image);
            fileHolderPreviewReInit("#howWeDo-edit input[name=image]");
            openModalBySelector("#howWeDo-edit");

        });

        $(".delete-modal-button").click(function(){
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute =  "{{ setRoute('admin.setup.sections.section.item.delete',$slug) }}";
            var target = oldData.id;
            var message     = `Are you sure to <strong>delete</strong> item?`;

            openDeleteModal(actionRoute,target,message);
        });
    </script>
@endpush
