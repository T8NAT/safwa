<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@if(isset($banner->value))
<section class="banner-section  bg_img" data-background="{{ get_image(@$banner->value->image, 'site-section') }}">
    <div class="element-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="819" height="98" viewBox="0 0 819 98" fill="none">
            <path opacity="0.1" d="M2 2L95.5 95.5H822" stroke="white" stroke-width="4"/>
        </svg>
    </div>
    <div class="element-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="1279" height="80" viewBox="0 0 1279 80" fill="none">
            <path opacity="0.1" d="M0.5 77.5H202.5L277 3H549.5L593 46.5H1279.5" stroke="white" stroke-width="4"/>
        </svg>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-lx-6 col-lg-7">
                <div class="banner-content">
                    <h1 class="title"><span class="text--base">{{ @$coloredText }}</span> {{ @$remainingText }}</h1>
                    <p>{{ @$banner->value->language->$lang->sub_heading }}</p>
                    <div class="banner-btn">
                        <a href="{{ url($banner->value->language->$lang->button_link ?? $banner->value->button_link) }}" class="btn--base">{{ @$banner->value->language->$lang->button_name }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
