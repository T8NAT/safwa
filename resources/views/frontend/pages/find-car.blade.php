@extends('frontend.layouts.master')
@section('content')
<!-- car-searching -->
<section class="car-finding-area">
    @include('frontend.sections.find-car-section')
</section>

<!-- serching data -->
<section class="car-list-area ptb-80">
    @include('frontend.sections.car-section')
</section>

@endsection
