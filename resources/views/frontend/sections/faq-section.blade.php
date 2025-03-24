<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Faq
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="faq-section pb-80">
    <div class="container">
        @if(@isset($faq->value))
        <div class="section-header-title">
            <div class="section-title pb-20">
                <h4 class="ssub-title text--base">{{@$faq->value->language->$lang->section_title ?? @$faq->value->language->$default->section_title }}</h4>
            </div>
            <div class="faq-title pb-20">
                <h2 class="title">{{@$faq->value->language->$lang->title ?? @$faq->value->language->$default->title }}</h2>
            </div>
        </div>
        <div class="row justify-content-center mb-20-none">
            <div class="col-xl-6 col-lg-6 mb-20">
                <div class="faq-wrapper">
                    @foreach ($faq->value->items ?? []  as $key => $item )
                    @if ($loop->iteration <= $half)
                        <div class="faq-item">
                            <h3 class="faq-title"><span class="title">{{ @$item->language->$lang->item_title ?? @$item->language->$default->item_title }}</span><span class="right-icon"></span></h3>
                            <div class="faq-content">
                                <p>{{ @$item->language->$lang->item_description ?? @$item->language->$default->item_description }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 mb-20">
                <div class="faq-wrapper">
                    @foreach ($faq->value->items ?? [] as $key => $item )
                        @if ($loop->iteration > $half)
                    <div class="faq-item">
                        <h3 class="faq-title"><span class="title">{{ @$item->language->$lang->item_title ?? @$item->language->$default->item_title }}</span><span class="right-icon"></span></h3>
                        <div class="faq-content">
                            <p>{{ @$item->language->$lang->item_description  ?? @$item->language->$default->item_description }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
