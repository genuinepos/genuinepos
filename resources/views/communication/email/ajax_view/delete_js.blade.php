<script>
    $(document).ready(function() {
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault(); 
        var id = $(this).data('id');
        var url = "{{ route('servers.destroy', ':id') }}"; 
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
                        $('.data_tbl').DataTable().ajax.reload();
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