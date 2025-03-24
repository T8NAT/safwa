@if (admin_permission_by_name("admin.contact.details"))
    <div id="message-details" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Message Details") }}</h5>
            </div>
            <div class="modal-form-data">
                <div class="col-lg-12 form-group">
                    <label>{{ __("Name") }}</label>
                    <input type="text"  class="form--control name" value="" readonly>
                </div>
                <div class="col-lg-12 form-group">
                    <label>{{ __("Email") }}</label>
                    <input type="email"  class="form--control email" value="" readonly>
                </div>
                <div class="col-lg-12 form-group">
                    <label>{{ __("Message") }}</label>
                    <textarea class="form--control message"  readonly></textarea>
                </div>
                <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                    <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                </div>
            </div>
        </div>
    </div>

    @push("script")
        <script>
           $(document).on("click", ".edit-modal-button", function() {
                var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
                var editModal = $("#message-details");

                editModal.find('.name').val(oldData.name);
                editModal.find('.email').val(oldData.email);
                editModal.find('.message').val(oldData.message);

                openModalBySelector("#message-details");
            });
        </script>
    @endpush
@endif
