<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script>
    $('#photo').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}"
        }
    });
    
    // Add user by ajax
    $(document).on('submit', '#update_profile_form', function(e) {
        e.preventDefault();

        $('.update_profile_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                toastr.success(data);
                $('.update_profile_loading_btn').hide();
                $('.error').html('');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.update_profile_loading_btn').hide();

                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    // Change password form submit by ajax
    $(document).on('submit', '#reset_password_form', function(e) {
        e.preventDefault();

        $('.change_password_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.change_password_loading_btn').hide();
                $('.error').html('');

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;

                }

                toastr.success(data.successMsg);
                window.location = "{{ route('login') }}";
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.change_password_loading_btn').hide();

                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
