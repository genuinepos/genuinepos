<script>
    $('#cashRegisterDetailsBtn').on('click', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#cashRegisterDetailsAndCloseModal').empty();
                $('#cashRegisterDetailsAndCloseModal').html(data);
                $('#cashRegisterDetailsAndCloseModal').modal('show');
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $('#closeCashRegisterBtn').on('click', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#cashRegisterDetailsAndCloseModal').empty();
                $('#cashRegisterDetailsAndCloseModal').html(data);
                $('#cashRegisterDetailsAndCloseModal').modal('show');

                setTimeout(function() {

                    $('#closing_note').focus().select();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>
