<script>
    $(document).on('click', '#showStockBtn', function (e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $.ajax({
            url : url,
            type:'get',
            success:function(data){

                $('#showStockModal').empty();
                $('#showStockModal').html(data);
                $('#showStockModal').modal('show');
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>
