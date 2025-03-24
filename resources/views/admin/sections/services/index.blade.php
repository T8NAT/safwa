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
    ], 'active' => __("Services")])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
                <div class="table-btn-area">
                    @include('admin.components.link.custom',[
                    'text'          => __("Add Service"),
                    'class'         => 'btn btn--base',
                    'href'          => setRoute('admin.service.create'),
                    'permission'    => 'admin.service.create',
                ])
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th>{{ __("Size") }}</th>
                            <th>{{ __("Booking Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services ?? [] as $key => $item)
                            <tr data-item="{{ $item }}">
                                <td>
                                    <ul class="user-list">
{{--                                        <li><img src="{{ get_image($item->image ?? '','site-section') ?? ''}}" alt="" srcset=""></li>--}}
                                        <li><img src="{{ asset('public/images/services/'.$item->image)}}" alt="" srcset=""></li>

                                    </ul>
                                </td>
                                <td>{{ $item->name ?? ""}}</td>
                                <td>
                                    @include('admin.components.form.switcher',[
                                        'name'        => 'status',
                                        'value'       => $item->status,
                                        'options'     => [__("Enable") => 1, __("Disable") => 0],
                                        'onload'      => true,
                                        'data_target' => $item->id,
                                    ])
                                </td>
                                <td>{{ $item->size ?? ""}}</td>
                                <td>
                                    @if(isset($item['booking_status']))
                                        @if($item['booking_status'] == 1)
                                            {{ __("Booked") }}
                                        @elseif ($item['booking_status'] == 2)
                                            {{ __("Ongoing") }}
                                        @elseif ($item['booking_status'] == 3)
                                            {{ __("Completed") }}
                                        @else
                                            {{ __("Reserved") }}
                                        @endif
                                    @else
                                        {{ __("Reserved") }}
                                    @endif
                                </td>
                                <td>
                                    @include('admin.components.link.edit-default',[
                                        'href'          => setRoute('admin.service.edit',$item->id),
                                        'class'         => "edit-modal-button",
                                        'permission'    => "admin.service.edit",
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
            var actionRoute = "{{ setRoute('admin.service.delete') }}";
            var target      = oldData.id;
            var message = `{{ __("Are you sure to delete this item?") }}`;

            openDeleteModal(actionRoute,target,message);
        });

         $(document).ready(function(){
            // Switcher
            switcherAjax("{{ setRoute('admin.service.status.update') }}");
        })
    </script>
@endpush
