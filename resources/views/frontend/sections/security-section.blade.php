<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        Start Security System
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
 <section class="security-section pt-80 pb-80">
    <div class="container">
        @if(@isset($security->value))
        <div class="section-header-title">
            <h4 class="title text--base pb-20">{{ @$security->value->language->$lang->section_title ?? @$security->value->language->$default->section_title}}</h4>
            <h2 class="title-head">{{ @$security->value->language->$lang->title ?? @$security->value->language->$default->title }}</h2>
        </div>
        <div class="security-area pt-30">
            <div class="row">
                @foreach ($security->value->items ?? []  as $key => $item )
                    <div class="col-lg-4 col-md-6 mb-20">
                        <div class="security-item">
                            <div class="icon">
                                <i class="{{ @$item->language->$lang->item_section_icon ?? @$item->language->$default->item_section_icon }}"></i>
                            </div>
                            <div class="security-details">
                                <h3 class="title">{{ @$item->language->$lang->item_title ?? @$item->language->$default->item_title }}</h3>
                                <p>{{ @$item->language->$lang->item_description ?? @$item->language->$default->item_description}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
 <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        End Security System
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
