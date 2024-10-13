<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Bank") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_bank_form" action="{{ route('banks.update', $bank->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>{{ __("Name") }}</b> : <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" id="bank_name" value="{{ $bank->name }}" data-next="bank_save_changes_btn" placeholder="{{ __("Bank name") }}"/>
                    <span class="error error_bank_name"></span>
                </div>

                <div class="form-group text-right mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button bank_loading_btn d-hide"><i
                                class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                            </button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" id="bank_save_changes_btn" class="btn btn-sm btn-success bank_submit_button">@lang('menu.save_changes')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.bank_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.bank_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_bank_form').on('submit', function(e) {
        e.preventDefault();

        $('.bank_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.bank_loading_btn').hide();

                $('#bankAddOrEditModal').modal('hide');
                $('#bankAddOrEditModal').empty();
                toastr.success("{{ __('Bank created successfully.') }}");
                bankTable.ajax.reload(false, null);
            },
            error: function(err) {

                $('.bank_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_bank_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });
</script>
