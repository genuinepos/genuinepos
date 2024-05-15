<script>
    $(document).ready(function() {

    $(document).on('click', '.delete-btn', function(e) {
     var status = parseInt($("#status_id").val(), 10);
        e.preventDefault(); 
        var id = $(this).data('id');
        var url = "{{ route('send.destroy', ':id') }}"; 
        url = url.replace(':id', id); 
        var confirmation = confirm("Are you sure?");
        if(confirmation){
            $.ajax({
                    url: url, 
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    success: function(response) {
                        getData(status);
                        toastr . success(response . message);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); 
                        toastr.error('Error deleting item.'); 
                    }
                });
        }
    });
});
</script>