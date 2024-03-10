<script>
$(document).on('submit', '#edit_data', function(event) {
    event.preventDefault();
    var formData = $(this).serialize();
    var id = $('#edit_input').val();
    var url = "{{ route('sms-server.update', ':id') }}"; 
    url = url.replace(':id', id); 
    $.ajax({
        url: url,
        type: 'PATCH',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('.data_tbl').DataTable().ajax.reload();
            toastr.success(response.message);
            document.getElementById("edit_data").reset();
            $('#edit_data').attr('id', 'add_data');
        },
             error: function(xhr, status, error) {
            var errorData = JSON.parse(xhr.responseText); 
            var errorMessage = "";
            if (errorData.errors) {
                Object.keys(errorData.errors).forEach(function(key) {
                    var errorMessages = errorData.errors[key];
                    errorMessages.forEach(function(message) {
                        errorMessage += message + "<br>";
                    });
                });
            } else {
                errorMessage = errorData.message;
            }
            toastr.error(errorMessage); 
        }
    });
});
</script>
