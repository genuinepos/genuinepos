<script>
    var tableRowIndex = 0;
    $(document).on('click', '#delete', function (e) {

        e.preventDefault();
        var parentTableRow = $(this).closest('tr');
        tableRowIndex = parentTableRow.index();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function () { $('#deleted_form').submit(); $('#recent_trans_preloader').show(); }
                },
                'No': { 'class': 'no btn-danger', 'action': function () { console.log('Deleted canceled.') } }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form', function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function (data) {

                toastr.error(data);

                $('#transection_list tr:nth-child(' + (tableRowIndex + 1) + ')').remove();
                $('#recent_trans_preloader').hide();
                $('#suspendedSalesModal').modal('hide');
                $('#holdInvoiceModal').modal('hide');
            }
        });
    });
</script>
