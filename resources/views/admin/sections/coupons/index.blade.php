@extends('admin.layouts.master')

@push('css')

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
    ], 'active' => __("Coupons")])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
                <div class="table-btn-area">
                    @include('admin.components.link.custom',[
                    'text'          => __("Add Coupon"),
                    'class'         => 'btn btn--base',
                    'href'          => setRoute('admin.coupon.create'),
                    'permission'    => 'admin.coupon.create',
                ])
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Code") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th>{{ __("Expiry Date") }}</th>
                            <th>{{ __("Discount") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons ?? [] as $key => $item)
                            <tr data-item="{{ $item }}">
                                <td>{{ $item->name ?? ""}}</td>
                                <td>{{ $item->code ?? ""}}</td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'        => 'status',
                                        'value'       => $item->status,
                                        'options'     => [__("Enable") => 1, __("Disable") => 0],
                                        'onload'      => true,
                                        'data_target' => $item->id,
                                    ])
                                </td>
                                <td>
                                    {{ $item->expiry_date ?? ""}}
                                </td>
                                <td>
                                    {{ $item->discount ?? ""}} {{'%'}}
                                </td>
                                <td>
                                    @include('admin.components.link.edit-default',[
                                        'href'          => setRoute('admin.coupon.edit',$item->id),
                                        'class'         => "edit-modal-button",
                                        'permission'    => "admin.coupon.edit",
                                    ])
                                    <button class="btn btn--base btn--danger delete-modal-button" ><i class="las la-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty',['colspan' => 5])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.delete-modal-button').click(function(){
            var oldData     = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ setRoute('admin.coupon.delete') }}";
            var target      = oldData.id;
            var message = `{{ __("Are you sure to delete this item?") }}`;

            openDeleteModal(actionRoute,target,message);
        });

         $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.coupon.status.update') }}");
        })
    </script>
@endpush
