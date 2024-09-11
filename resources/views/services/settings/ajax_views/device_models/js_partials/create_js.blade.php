<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.device_model_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.device_model_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_device_model_form').on('submit', function(e) {
        e.preventDefault();

        $('.device_model_loading_btn').show();
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
                $('.device_model_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success("{{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] . ' ' . __('is added successfully') : __('Device model is added successfully') }}");
                    $('#deviceModelAddOrEditModal').modal('hide');
                    var device_model_id = $('#device_model_id').val();

                    if (device_model_id != undefined) {

                        $('#device_model_id').append('<option data-checklist="' + data.service_checklist + '" value="' + data.id + '">' + data.name + '</option>');
                        $('#device_model_id').val(data.id);

                        if (typeof getCheckList != 'undefined') {

                            getCheckList();
                        }

                        var nextId = $('#device_model_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        deviceModelsTable.ajax.reload();
                    }
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.device_model_loading_btn').hide();
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

                    $('.error_device_model_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
