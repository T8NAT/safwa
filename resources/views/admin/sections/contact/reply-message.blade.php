@if (admin_permission_by_name("admin.contact.reply"))
    <div id="message-reply" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __("Reply Message") }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ setRoute('admin.contact.reply') }}">
                    @csrf
                    <input type="hidden" name="id" id="reply-message-id" value="">
                    <input type="hidden" name="user_email" id="reply-user-email" value="">

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input',[
                            'label'         => __("Subject").'*',
                            'name'          => 'subject',
                            'value'         => old('subject'),
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input-text-rich',[
                            'label'         => __("Message").'*',
                            'name'          => 'message',
                            'placeholder'   => __("Write Here").'...',
                            'value'         => old('message'),
                        ])
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                        <button type="button" class="btn btn--danger modal-close">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn--base">{{ __("Sent") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("script")
        <script>
            $(document).ready(function(){
                openModalWhenError("message_reply","#message-reply");
                $(document).on("click",".reply-modal-button",function(){
                    var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
                    var editModal = $("#message-reply");
                    var id = oldData.id;
                    var userEmail = oldData.email;
                    $("#reply-message-id").val(id);
                    $('#reply-user-email').val(userEmail);
                    openModalBySelector("#message-reply");
                });
            });
        </script>
    @endpush
@endif
