<script>
    // Add user by ajax
    $('#payment_method_settings_form').on('submit', function(e) {
        e.preventDefault();

        $('.payment_method_settings_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.payment_method_settings_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                setTimeout(function() {

                    $('#account_id').focus();
                }, 100);
            },
            error: function(err) {

                $('.payment_method_settings_loading_btn').hide();
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
            }
        });
    });

    function getPaymentMethodSettingsView() {

        var url = "{{ route('payment.methods.settings.view') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#payment_method_settings_body').empty();
                $('#payment_method_settings_body').html(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }
    getPaymentMethodSettingsView();

    $(document).on('change keypress click', '#account_id', function(e) {

        var next = $(this).closest('tr').next();

        if (e.which == 0) {

            if (next.length > 0) {

                next.find('#account_id').focus();
            } else {

                $('#save_payment_settings_save_changes').focus();
            }
        }
    });
</script>
