@if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
    <script>
        // Pick hold invoice
        $(document).on('click', '#pick_hold_btn', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('#holdInvoiceModal').empty();
                    $('#holdInvoiceModal').html(data);
                    $('#holdInvoiceModal').modal('show');
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
@endif
