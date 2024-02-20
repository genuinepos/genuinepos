<script>
    var removableDom = '';
    $(document).on('click', '#delete', function(e) {

        e.preventDefault();
        // var parentTableRow = $(this).closest('tr');
        // tableRowIndex = parentTableRow.index();
        var parentTr = $(this).closest('tr');
        var parentSection = $(this).closest('section');

        if (parentTr.length > 0) {

            removableDom = parentTr;
        } else if (parentSection.length > 0) {

            removableDom = parentSection;
        }

        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-danger',
                    'action': function() {
                        console.log('Deleted canceled.')
                    }
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.error("{{ __('Data deleted successfully') }}");
                $(removableDom).remove();
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
