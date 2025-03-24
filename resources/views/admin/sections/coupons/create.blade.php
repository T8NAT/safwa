@extends('admin.layouts.master')

@push('css')

    <style>
        .fileholder {
            min-height: 200px !important;
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
        ],
        [
            'name'  => __("Services"),
            'url'   => setRoute("admin.service.index")
        ]
    ], 'active' => __("Coupon Create")])
@endsection

@section('content')
<div class="custom-card">
    <div class="card-header">
        <h6 class="title">{{ __($page_title) }}</h6>
    </div>
    <div class="card-body">
        <form class="card-form" action="{{ setRoute('admin.coupon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center mb-10-none">
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'label'             => __("Name")."*",
                        'name'              => "name",
                        'placeholder'       => __("Write Name")."...",
                        'value'             => old("name"),
                    ])
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label for="code">{{ __("Code") }}*</label>
                    <div class="input-group">
                    @include('admin.components.form.input',[
                        'name'              => "code",
                        'placeholder'       => "...",
                        'value'             => old("code"),
                        'id'                =>'code'
                    ])
                    <button class="btn btn--base" type="button" id="generateCodeBtn">{{ __("Generate") }}</button>
                </div>
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    <label>{{__("Discount")}}*</label>
                    <div class="input-group">
                    @include('admin.components.form.input',[
                        'name'              => "discount",
                        'class'             => "number-input",
                        'placeholder'       => __("Write Discount")."...",
                        'value'             => old("discount"),
                    ])
                        <span class="input-group-text">{{'%'}}</span>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 form-group">
                    @include('admin.components.form.input',[
                        'type' => 'date',
                        'label'             => __("Expiry Date")."*",
                        'name'              => "expiry_date",
                        'class'             => "date-input",
                        'placeholder'       => __("Enter Date")."...",
                        'value'             => old("expiry_date"),
                    ])
                </div>
                <div class="col-xl-12 col-lg-12 form-group">
                    @include('admin.components.button.form-btn',[
                        'class'         => "w-100 btn-loading",
                        'text'          => __("Submit"),
                        'permission'    => "admin.coupon.store"
                    ])
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#generateCodeBtn').on('click', function() {
                var code = generateRandomCode(15);

                $('#code').val(code);
            });

            function generateRandomCode(length) {
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var code = '';
                for (var i = 0; i < length; i++) {
                    code += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return code;
            }
        });
    </script>
@endpush

