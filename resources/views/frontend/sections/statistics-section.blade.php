<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    statistics-section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@if(@isset($statistics->value))
<section class="statistics-section bg-overlay-base bg_img" data-background="{{ get_image(@$statistics->value->image, 'site-section') }}">
    <div class="container">
        <div class="row text-center">
            @foreach ($statistics->value->items ?? []  as $key => $item )
            <div class="col-lg-4 col-md-4 col-sm-6 pb-20">
                <div class="counter">
                     <div class="icon">
                        <i class="{{ @$item->language->$lang->section_icon ?? @$item->language->$default->section_icon }}"></i>
                     </div>
                    <div class="odo-area">
                        <h2 class="odo-title odometer" data-odometer-final="{{ @$item->language->$lang->heading ?? @$item->language->$default->heading }}">0</h2>
                    </div>
                    <h4 class="title">{{ @$item->language->$lang->sub_heading ?? @$item->language->$default->sub_heading }}</h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
