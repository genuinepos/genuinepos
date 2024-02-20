<script>
    function cancel() {

        $.confirm({
            'title': "{{ __('Cancel Confirmation') }}",
            'content': "{{ __('Are you sure to cancel ?') }}",
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function () {

                        $('#product_list').empty();
                        $('#pos_submit_form')[0].reset();

                        calculateTotalAmount();

                        toastr.error("{{ __('Sale has been cancelled.') }}");
                        document.getElementById('search_product').focus();
                        var store_url = $('#store_url').val();
                        $('#pos_submit_form').attr('action', store_url);
                        activeSelectedItems();
                    }
                },
                'No': { 'class': 'no btn-danger', 'action': function () { console.log('Deleted canceled.'); } }
            }
        });
    }
</script>
