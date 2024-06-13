<script>
    $('#dashboard_settings_form').on('submit', function(e) {
        e.preventDefault();
        $('.dashboar_settings_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.dashboar_settings_loading_btn').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);

            }, error: function(err) {

                $('.dashboar_settings_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
