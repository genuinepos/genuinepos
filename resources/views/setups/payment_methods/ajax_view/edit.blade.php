<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Payment Method") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_payment_method_form" action="{{ route('payment.methods.update', $method->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>{{ __('Method Name') }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="payment_method_name" data-next="save_payment_method" value="{{ $method->name }}" placeholder="{{ __('Method Name') }}"/>
                        <span class="error error_payment_method_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button payment_method_loading_btn d-hide"><i class="fas fa-spinner"></i><span>{{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="save_payment_method" class="btn btn-sm btn-success payment_method_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.payment_method_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.payment_method_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_payment_method_form').on('submit', function(e) {
        e.preventDefault();

        $('.payment_method_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.payment_method_loading_btn').hide();
                $('#paymentMethodAddOrEditModal').modal('hide');
                $('#paymentMethodAddOrEditModal').empty();
                toastr.success(data);
                paymentMethodTable.ajax.reload();
                getPaymentMethodSettingsView();
            },
            error: function(err) {

                $('.payment_method_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_payment_method_' + key + '').html(error[0]);
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

