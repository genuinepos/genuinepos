<script>
    $('#add_sale_settings_form').on('submit', function(e) {
        e.preventDefault();
        $('.add_sale_settings_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                $('.error').html('');
                $('.add_sale_settings_loading_btn').hide();
            },
            error: function(err) {

                $('.add_sale_settings_loading_btn').hide();
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
</script>
