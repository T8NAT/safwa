@extends('user.layouts.master')
@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div class="body-wrapper">
                <div class="table-area mt-10">
                    <div class="table-wrapper">
                        <div class="dashboard-header-wrapper">
                            <h4 class="title">{{ __("History List") }}</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("Trip Id") }}</th>
                                        <th>{{ __("Pick-up Location") }}</th>
                                        <th>{{ __("Destination") }}</th>
                                        <th>{{ __("Pick-up Date & Time") }}</th>
                                        <th>{{ __("Round Trip") }}</th>
                                        <th>{{ __("Rate") }}</th>
                                        <th>{{ __("Total Distance") }}</th>
                                        <th>{{ __("Payable Amount") }}</th>
                                        <th>{{ __("Status") }}</th>
                                        <th>{{ __("Action") }}</th>
                                    </tr>
                                </thead>
                                <tbody> @forelse($booking as $item)
                                    <tr>
                                        <td data-label="{{ __("Trip Id") }}">{{ $item->trip_id }}</td>
                                        <td data-label="{{ __("Pick-up Location") }}">{{ $item->location }}</td>
                                        <td data-label="{{ __("Destination") }}">{{ $item->destination }}</td>
                                        <td data-label="{{ __("Pick-up Date & Time") }}">
                                            <div>
                                                {{
                                                    $item->pickup_date
                                                    ? \Carbon\Carbon::parse($item->pickup_date)->format('d-m-Y')  : ''
                                                }}
                                            </div>
                                            <div>
                                                {{
                                                    $item->pickup_time ? \Carbon\Carbon::parse($item->pickup_time)->format('h:i A') : ''

                                                }}
                                            </div>
                                        </td>
                                        <td data-label="{{ __("Round Trip") }}">
                                            <div>
                                                {{ $item->round_pickup_date ?  __("Yes")  : __("No") }}
                                            </div>
                                            @if($item->round_pickup_date)
                                                <div>
                                                    {{  $item->round_pickup_date
                                                        ? \Carbon\Carbon::parse($item->round_pickup_date)->format('d-m-Y') : ''
                                                    }}
                                                </div>
                                                <div>
                                                    {{  $item->round_pickup_time ? \Carbon\Carbon::parse($item->round_pickup_time)->format('h:i A') : ''

                                                    }}
                                                </div>
                                            @endif
                                        </td>
                                        <td data-label="{{ __("Rate") }}">{{ get_amount($item->cars->fees)  }}{{ __("/KM") }} {{ $default_currency->code }}</td>
                                        <td data-label="{{ __("Total Distance") }}">{{ ($item->distance == 0) ? __("N/A") : get_amount($item->distance,__("KM"),2) }}</td>
                                        <td data-label="{{ __("Payable Amount") }}" >{{ ($item->amount== 0) ? __("N/A") : get_amount($item->amount,$default_currency->code,2) }} </td>
                                        <td> @if($item->status == 1) {{ __("Booked") }} @elseif ($item->status == 2) {{ __("OnGoing") }} @else {{ __("Completed") }} @endif</td>
                                        <td> <a href="{{ setRoute('user.history.details',$item->slug)}}" class="btn btn--base btn--primary"><i class="las la-info-circle"></i></a></td>
                                     </tr>
                                    @empty
                                        @include('user.components.alerts.empty', ['colspan' => 10])
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ get_paginate($booking) }}
                </div>
            </div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection
