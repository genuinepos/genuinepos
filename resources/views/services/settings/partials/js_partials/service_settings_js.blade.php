<script>
    $(document).ready(function() {
        function formatState(state) {
            if (!state.id) {
                return state.text; // optgroup
            }

            var icon = $(state.element).data('icon');
            var color = $(state.element).data('color');

            var $state = $(
                '<span><i class="' + icon + '" style="color:' + color + '"></i> ' + state.text + '</span>'
            );
            return $state;
        };

        $("#service_settings_default_status_id").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
    });

    $('#add_service_settings_form').on('submit', function(e) {
        e.preventDefault();

        $('.service_settings_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.service_settings_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
            },
            error: function(err) {

                $('.service_settings_loading_btn').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
            }
        });
    });
</script>
