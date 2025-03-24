<!-- notify js -->
<script src='{{ asset('public/backend/js/bootstrap-notify.min.js') }}'></script>

@if ($basic_settings->google_api_key != null)
    @php
        $google_api_key = $basic_settings->google_api_key;
    @endphp
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ $google_api_key }}&libraries=places&callback=inputAutocompleteCallback">
    </script>
@endif

<script>
    // Show Laravel Error Messages----------------------------------------------
    $(function () {
        $(document).ready(function(){
            @if (session('error'))
                @if (is_array(session('error')))
                    @foreach (session('error') as $item)
                        $.notify(
                            {
                                title: "",
                                message: "{{ __($item) }}",
                                icon: 'las la-exclamation-triangle',
                            },
                            {
                                type: "danger",
                                allow_dismiss: true,
                                delay: 5000,
                                placement: {
                                from: "top",
                                align: "right"
                                },
                            }
                        );
                    @endforeach
                @endif
            @elseif (session('success'))
                @if (is_array(session('success')))
                    @foreach (session('success') as $item)
                        $.notify(
                            {
                                title: "",
                                message: "{{ __($item) }}",
                                icon: 'las la-check-circle',
                            },
                            {
                                type: "success",
                                allow_dismiss: true,
                                delay: 5000,
                                placement: {
                                from: "top",
                                align: "right"
                                },
                            }
                        );
                    @endforeach
                @endif
            @elseif (session('warning'))
                @if (is_array(session('warning')))
                    @foreach (session('warning') as $item)
                        $.notify(
                            {
                                title: "",
                                message: "{{ __($item) }}",
                                icon: 'las la-exclamation-triangle',
                            },
                            {
                                type: "warning",
                                allow_dismiss: true,
                                delay: 500000000,
                                placement: {
                                from: "top",
                                align: "right"
                                },
                            }
                        );
                    @endforeach
                @endif
            @elseif ($errors->any())
                @foreach ($errors->all() as $item)
                    $.notify(
                        {
                            title: "",
                            message: "{{ __($item) }}",
                            icon: 'las la-exclamation-triangle',
                        },
                        {
                            type: "danger",
                            allow_dismiss: true,
                            delay: 5000,
                            placement: {
                            from: "top",
                            align: "right"
                            },
                        }
                    );
                @endforeach
            @endif
        });
    });
    //--------------------------------------------------------------------------

    // Function for throw error messages from javascript------------------------
    function throwMessage(type,errors = []) {
        if(type == 'error') {
            $.each(errors,function(index,item) {
                $.notify(
                    {
                        title: "",
                        message: item,
                        icon: 'las la-exclamation-triangle',
                    },
                    {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 5000,
                        placement: {
                        from: "top",
                        align: "right"
                        },
                    }
                );
            });
        }else if(type == 'success') {
            $.each(errors,function(index,item) {
                $.notify(
                    {
                        title: "",
                        message: item,
                        icon: 'las la-check-circle',
                    },
                    {
                        type: "success",
                        allow_dismiss: true,
                        delay: 5000,
                        placement: {
                        from: "top",
                        align: "right"
                        },
                    }
                );
            });
        }else if(type == 'warning') {
            $.each(errors,function(index,item) {
                $.notify(
                    {
                        title: "",
                        message: item,
                        icon: 'las la-check-circle',
                    },
                    {
                        type: "warning",
                        allow_dismiss: true,
                        delay: 500000000,
                        placement: {
                        from: "top",
                        align: "right"
                        },
                    }
                );
            });
        }

    }
    //--------------------------------------------------------------------------

    // Function for set modal session value --------------------
    var validationSession = null;
    function getSessionValue(sesesionValue = null) {
        validationSession = sessionValue;
    }

    @if (session('modal'))
        var sessionValue = "{{ session('modal') }}";
        getSessionValue(sessionValue);
    @endif

    // Function for open modal/popup when have backend session
    function openModalWhenError(sessionValue,modalSelector) {
        if(validationSession != sessionValue) {
            return false;
        }
        openModalBySelector(modalSelector);
    }
    //----------------------------------------------------------


    function countrySelect(element,errorElement) {
        $(document).on("change",element,function(){
            var targetElement = $(this);
            var countryId = $(element+" :selected").attr("data-id");
            if(countryId != "" || countryId != null) {
                var CSRF = $("meta[name=csrf-token]").attr("content");
                var data = {
                    _token      : CSRF,
                    country_id  : countryId,
                };
                $.post("{{ setRoute('global.country.states') }}",data,function() {
                    // success
                    $(errorElement).removeClass("is-invalid");
                    $(targetElement).siblings(".invalid-feedback").remove();
                }).done(function(response){
                    // Place States to States Field
                    var options = "<option selected disabled>Select State</option>";
                    $.each(response,function(index,item) {
                        options += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                    $(".state-select").html(options);
                }).fail(function(response) {
                    if(response.status == 422) { // Validation Error
                        var faildMessage = "Please select a valid Country.";
                        var faildElement = `<span class="invalid-feedback" role="alert">
                                                <strong>${faildMessage}</strong>
                                            </span>`;
                        $(errorElement).addClass("is-invalid");
                        if($(targetElement).siblings(".invalid-feedback").length != 0) {
                            $(targetElement).siblings(".invalid-feedback").text(faildMessage);
                        }else {
                            errorElement.after(faildElement);
                        }
                    }else {
                        var faildMessage = "Something went worng! Please try again.";
                        var faildElement = `<span class="invalid-feedback" role="alert">
                                                <strong>${faildMessage}</strong>
                                            </span>`;
                        $(errorElement).addClass("is-invalid");
                        if($(targetElement).siblings(".invalid-feedback").length != 0) {
                            $(targetElement).siblings(".invalid-feedback").text(faildMessage);
                        }else {
                            errorElement.after(faildElement);
                        }
                    }

                });
            }else {
                // Push Error
            }
        });
    }

    // State Select Get Cities
    function stateSelect(element,errorElement) {
        $(document).on("change",element,function(){
            var targetElement = $(this);
            var stateId = $(element+" :selected").attr("data-id");
            if(stateId != "" || stateId != null) {
                var CSRF = $("meta[name=csrf-token]").attr("content");
                var data = {
                    _token      : CSRF,
                    state_id  : stateId,
                };
                $.post("{{ setRoute('global.country.cities') }}",data,function(response) {
                    // success
                    $(errorElement).removeClass("is-invalid");
                    $(targetElement).siblings(".invalid-feedback").remove();
                }).done(function(response){
                    console.log("success", response);
                    // Place States to States Field
                    var options = "<option selected disabled>Select City</option>";
                    $.each(response,function(index,item) {
                        options += `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });

                    $(".city-select").html(options);
                }).fail(function(response) {
                    if(response.status == 422) { // Validation Error
                        var faildMessage = "Please select a valid state.";
                        var faildElement = `<span class="invalid-feedback" role="alert">
                                                <strong>${faildMessage}</strong>
                                            </span>`;
                        $(errorElement).addClass("is-invalid");
                        if($(targetElement).siblings(".invalid-feedback").length != 0) {
                            $(targetElement).siblings(".invalid-feedback").text(faildMessage);
                        }else {
                            errorElement.after(faildElement);
                        }
                    }else {
                        var faildMessage = "Something went worng! Please try again.";
                        var faildElement = `<span class="invalid-feedback" role="alert">
                                                <strong>${faildMessage}</strong>
                                            </span>`;
                        $(errorElement).addClass("is-invalid");
                        if($(targetElement).siblings(".invalid-feedback").length != 0) {
                            $(targetElement).siblings(".invalid-feedback").text(faildMessage);
                        }else {
                            errorElement.after(faildElement);
                        }
                    }
                });
            }else {
                // Push Error
            }
        });
    }

    function loadGoogleAutocompleteInput(countryCode) {
        inputAutocompleteCallback(countryCode);
    }

    function inputAutocompleteCallback(country_code = null) {
        var input_all = document.querySelectorAll(".place-input");
            input_all.forEach(function(input){
                if(input != undefined && country_code != null) {
                const options = {
                    componentRestrictions: { country: [country_code] },
                    fields: ["address_components", "geometry"],
                    types: ["address"],
                };

                const autocomplete = new google.maps.places.Autocomplete(input, options);

                var readableAddress;
                autocomplete.addListener("place_changed",function() {
                    readableAddress = {
                        route: "",
                        street_number: "",
                        city: "",
                        state: "",
                        subCity: "",
                        country: "",
                        postal_code: "",
                    };
                    var detailsPlaceInfo = autocomplete.getPlace();
                    detailsPlaceInfo.address_components.forEach(function(item) {
                        var addressType = item.types;
                        if(addressType.includes("route")) {
                            readableAddress.route = item.long_name;
                        }else if(addressType.includes("street_number")) {
                            readableAddress.street_number = item.long_name;
                        }else if(addressType.includes("locality")) {
                            readableAddress.city = item.long_name;
                        }else if(addressType.includes("administrative_area_level_1")) {
                            readableAddress.state = item.long_name;
                        }else if(addressType.includes("sublocality")) {
                            readableAddress.subCity = item.long_name;
                        }else if(addressType.includes("country")) {
                            readableAddress.country = item.long_name;
                        }else if(addressType.includes("postal_code")) {
                            readableAddress.postal_code = item.long_name;
                        }
                    });
                    $("input[name=state]").val(readableAddress.state);
                    $("input[name=city]").val(readableAddress.city);
                    $("input[name=postal_code]").val(readableAddress.postal_code);
                    $("input[name=zip]").val(readableAddress.postal_code);
                });
            }
        })


    }

</script>

@php

@endphp
