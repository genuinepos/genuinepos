<script>
    $(document).on('change', '#cash_account_id', function() {

        $('#opening_cash').val(parseFloat(0).toFixed(2));

        var accountId = $(this).val();
        if (accountId == '') {

            return;
        }

        var branchId = "{{ auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id }}";
        var filterObj = {
            branch_id: branchId,
            from_date: null,
            to_date: null,
        };

        var url = "{{ route('accounts.balance', ':accountId') }}";
        var route = url.replace(':accountId', accountId);

        $.ajax({
            url: route,
            type: 'get',
            data: filterObj,
            success: function(data) {

                $('#opening_cash').val(parseFloat(data.closing_balance_in_flat_amount).toFixed(2));
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });
</script>
