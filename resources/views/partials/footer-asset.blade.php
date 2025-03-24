<!-- jquery -->
<script src="{{ asset('public/frontend/assets/js/jquery-3.6.0.js') }}"></script>
<!-- bootstrap js -->
<script src="{{ asset('public/frontend/assets/js/bootstrap.bundle.js') }}"></script>
<!-- swipper js -->
<script src="{{ asset('public/frontend/assets/js/swiper.js') }}"></script>
<!-- lightcase js-->
<script src="{{ asset('public/frontend/assets/js/lightcase.js') }}"></script>
<!-- odometer js -->
<script src="{{ asset('public/frontend/assets/js/odometer.js') }}"></script>
<!-- viewport js -->
<script src="{{ asset('public/frontend/assets/js/viewport.jquery.js') }}"></script>
<!-- AOS js -->
<script src="{{ asset('public/frontend/assets/js/aos.js') }}"></script>
<!-- smooth scroll js -->
<script src="{{ asset('public/frontend/assets/js/smoothscroll.js') }}"></script>
<!-- nice select js -->
<script src="{{ asset('public/frontend/assets/js/jquery.nice-select.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('public/frontend/assets/js/select2.js') }}"></script>
<!-- main -->
<script src="{{ asset('public/frontend/assets/js/main.js') }}"></script>
<!--  Popup -->
<script src="{{ asset('public/backend/library/popup/jquery.magnific-popup.js') }}"></script>
<!-- file holder js -->
<script src="https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js" type="module"></script>

<script>
    var swiper = new Swiper('.testimonial', {
      spaceBetween: 30,
      effect: 'fade',
      loop: true,
      mousewheel: {
        invert: false,
      },
      // autoHeight: true,
      pagination: {
        el: '.testimonial__pagination',
        clickable: true,
      }
    });
</script>
<script type="module">
    import { fileHolderSettings } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-settings.js";
    import { previewFunctions } from "https://appdevs.cloud/cdn/fileholder/v1.0/js/fileholder-script.js";
    var inputFields = document.querySelector(".file-holder");
    fileHolderAfterLoad.previewReInit = function(inputFields){
        previewFunctions.previewReInit(inputFields)
    };
    fileHolderSettings.urls.uploadUrl = "{{ setRoute('fileholder.upload') }}";
    fileHolderSettings.urls.removeUrl = "{{ setRoute('fileholder.remove') }}";
</script>

<script>
    function fileHolderPreviewReInit(selector) {
        var inputField = document.querySelector(selector);
        fileHolderAfterLoad.previewReInit(inputField);
    }
</script>
