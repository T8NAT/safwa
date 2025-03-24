<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End testimonial
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="testimonial-section ptb-80">
    <div class="container">
        @if(@isset($testimonial->value))
        <div class="testimonail-title text-center">
            <div class="section-title pb-20">
                <h4 class="sub-title text--base">{{@$testimonial->value->language->$lang->section_title ?? @$testimonial->value->language->$default->section_title }}</h4>
            </div>
            <div class="testimonial-title pb-30">
                <h2 class="title">{{@$testimonial->value->language->$lang->title ?? @$testimonial->value->language->$default->title }}</h2>
            </div>
        </div>
        <div class="testimonial">
            <div class="testimonial__wrp swiper-wrapper">
                @foreach ($testimonial->value->items ?? []  as $key => $item )
                <div class="testimonial__item swiper-slide">
                    <div class="testimonial__img">
                        <img src="{{ get_image($item->image ?? "","site-section") }}" alt="testimonial">
                    </div>
                    <div class="testimonial__content">
                        <span class="testimonial__code">{{ (new DateTime($item->created_at))->format('d F, Y') }}</span>
                        <div class="testimonial__title">{{ @$item->language->$lang->item_name ?? @$item->language->$default->item_name}}</div>
                        <div class="testimonial__text"><p>{{ @$item->language->$lang->item_description ?? @$item->language->$default->item_description}} </p></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="testimonial__pagination"></div>
        </div>
        @endif
    </div>
</section>

