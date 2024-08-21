<script>
$(document).on('submit', '#add_data', function(event) {
    event.preventDefault(); 
    var formData = $(this).serialize(); 
    $.ajax({
        url: "{{ route('sms-body.store') }}",
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
        },
        success: function(response) {
            $('.data_tbl').DataTable().ajax.reload(); 
            toastr.success(response.message); 
            document.getElementById("add_data").reset(); 
            Object.values(window.editors).forEach(editor => {
                editor.setData('');
            });
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

