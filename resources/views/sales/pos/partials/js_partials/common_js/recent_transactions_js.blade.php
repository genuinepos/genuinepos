<script>
    $(document).on('click', '#recentTransactionsBtn', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#recentTransModal').empty();
                $('#recentTransModal').html(data);
                $('#recentTransModal').modal('show');
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
