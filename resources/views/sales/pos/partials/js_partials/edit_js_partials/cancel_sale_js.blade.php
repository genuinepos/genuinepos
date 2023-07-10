<script>
    function cancel() {

        $.confirm({
            'title': 'Cancel Confirmation',
            'content': 'Are you sure to cancel ?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function () {

                        cancel();
                    }
                },
                'No': { 'class': 'no btn-danger', 'action': function () { console.log('Deleted canceled.'); } }
            }
        });
    }

    function cancel() {

        toastr.error('Sale is cancelled.');
        window.location = "{{ route('sales.pos.create') }}";
    }
</script>
