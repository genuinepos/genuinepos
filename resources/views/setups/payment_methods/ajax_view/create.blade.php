<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Payment Method') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_payment_method_form" action="{{ route('payment.methods.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>{{ __('Method Name') }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="payment_method_name" data-next="save_payment_method" placeholder="{{ __('Method Name') }}" />
                        <span class="error error_payment_method_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button payment_method_loading_btn d-hide"><i class="fas fa-spinner"></i><span>{{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="save_payment_method" class="btn btn-sm btn-success payment_method_submit_button">{{ __('Save') }}</button>
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

    $('#add_payment_method_form').on('submit', function(e) {
        e.preventDefault();

        $('.payment_method_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.payment_method_loading_btn').hide();
                $('#paymentMethodAddOrEditModal').modal('hide');
                $('#paymentMethodAddOrEditModal').empty();
                toastr.success(data);
                paymentMethodTable.ajax.reload();
                getPaymentMethodSettingsView();
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.payment_method_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
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

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });
</script>
