
<!-- serching data -->
    <div class="container">
        @if(@isset($cars))
        <div class="row justify-content-center">
            @forelse ($cars as $item)
            <div class="col-lg-6 col-md-10 pb-20">
                <div class="car-item">
                    <div class="car-img">
                        <img src="{{ get_image($item->image ?? '','site-section') ?? '' }}" alt="img">
                    </div>
                    <div class="car-details">
                        <h3 class="title">{{ $item->car_model ?? "" }}</h3>
                        <p>{{ $item->car_number ?? "" }}</p>
                        <p>{{ __("Total Seat ") }}{{ $item->seat ?? "" }}</p>
                        <p>{{ __("Per Km") }} {{ get_amount($item->fees) }} {{ $default_currency->code }}</p>
                        <p>{{ $item->experience ?? "" }}{{ __(" Year Experience") }}</p>
                        <div class="booking-btn">
                            <a href="{{ setRoute('frontend.car.booking.index',$item->slug)}}" class="btn--base">{{ __("Book Now") }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                    <div class="col-lg-12">
                        <h5 class="text-danger text-center">{{ __("Car not found!") }}</h5>
                    </div>
            @endforelse
        </div>
        @endif
    </div>
