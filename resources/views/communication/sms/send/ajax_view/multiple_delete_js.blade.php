<script>
$('.all_delete').click(function() {
          var status = parseInt($("#status_id").val(), 10);
            var ids = [];
            $('.checkbox-item:checked').each(function() {
                ids.push($(this).data('id'));
            });

            if (ids.length > 0) {
                $.ajax({
                    url: '{{ route('delete_sms_multiple') }}',
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { ids: ids },
                    success: function(response) {
                       console.log(response);
                       getData(status);
                       toastr . success(response . message);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                alert('Please select at least one record to delete');
            }
        });

        $('#permanent_delete').click(function() {
          var status = parseInt($("#status_id").val(), 10);
            var ids = [];
            $('.checkbox-item:checked').each(function() {
                ids.push($(this).data('id'));
            });

            if (ids.length > 0) {
                $.ajax({
                    url: '{{ route('delete_sms_permanent') }}',
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { ids: ids },
                    success: function(response) {
                       console.log(response);
                       getData(status);
                       toastr . success(response . message);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                alert('Please select at least one record to delete');
            }
   });
</script>        