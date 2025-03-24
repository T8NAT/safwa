<div class="banner-flotting-section {{ $class ?? "" }}">
    <div class="container">
        <div class="banner-flotting-item">
            <form class="banner-flotting-item-form"  method="GET" action="{{ setRoute('frontend.car.search') }}">
                @csrf
                <div class="form-group">
                    @php
                        $old_area = request()->get('area');
                        $old_type = request()->get('type');
                    @endphp

                    <select class="form--control select2-basic"  name="area" spellcheck="false" data-ms-editor="true">
                        <option disabled selected>{{ __("Select Area") }}</option>
                        @foreach ($areas as $area)
                            <option {{ $old_area == $area->id ? "selected" : "" }} value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <select class="form--control select2-basic" name="type" spellcheck="false" data-ms-editor="true">
                        <option disabled selected>{{ __("Select Type") }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn--base search-btn w-100"><i class="fas fa-search me-1"></i> {{ __("Search") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script')
    <script>

            var getTypeURL = "{{ setRoute('frontend.get.area.types') }}";
            var old_type = "{{ $old_type }}";
            $(document).ready(function () {
                getAreaItems();
            });
            $('select[name="area"]').on('change',function(){
                getAreaItems();
            });
            function getAreaItems(){
                var area = $('select[name="area"]').val();

                if(area == "" || area == null) {
                    return false;
                }

                $.post(getTypeURL,{area:area,_token:"{{ csrf_token() }}"},function(response){
                    console.log(response);
                    var option = '';
                    if(response.data.area.types.length > 0) {
                        $.each(response.data.area.types,function(index,item) {
                            if(item.type != null){
                                var selected_item = old_type == item.car_type_id ? "selected" : "";
                                option += `<option ${ selected_item } value="${item.car_type_id}">${item.type.name}</option>`
                            }

                        });

                        $("select[name=type]").html(option);
                        $("select[name=type]").select2();

                    }
                }).fail(function(response) {
                    var errorText = response.responseJSON;
                    throwMessage('failed',['An error occurred.']);
                });

            }
    </script>
@endpush
