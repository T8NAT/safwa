<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End team section
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="why-choose-us pb-80">
    <div class="container">
        @if(@isset($why_choose_us->value))
        <div class="row">
            <div class="col-lg-8">
                <div class="section-title pb-20">
                    <h4 class="sub-title text--base">{{ @$why_choose_us->value->language->$lang->section_title ?? @$why_choose_us->value->language->$default->section_title }}</h4>
                </div>
                <div class="choose-us-title pb-20">
                    <h2 class="title">{{ @$why_choose_us->value->language->$lang->title ?? @$why_choose_us->value->language->$default->title }}</h2>
                </div>
                 <div class="choose-us-content">
                    <p>{{ @$why_choose_us->value->language->$lang->description ?? @$why_choose_us->value->language->$default->description }}</p>
                 </div>
            </div>
        </div>
        <div class="choose-us-area pt-60">
            <div class="row">
                @foreach($why_choose_us->value->items ?? [] as $key => $item)
                <div class="col-lg-6 mb-20">
                    <div class="choose-us-area" data-aos="fade-left" data-aos-duration="1200">
                        <div class="number">
                            <h3 class="title">{{ @$item->language->$lang->item_title ?? @$item->language->$default->item_title  }}</h3>
                        </div>
                        <div class="work-content tri-right left-top">
                            <p>{{ @$item->language->$lang->item_description ??@$item->language->$default->item_description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
