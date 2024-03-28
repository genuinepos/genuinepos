<script>
$(document).on('submit', '#mail_send', function(event) {
    event.preventDefault();
  var formData = new FormData($(this)[0]);
    $.ajax({
        url: "{{ route('send.store') }}",
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            if(response.status=='error'){
                toastr.error(response.message);
                return false;
            }
            toastr . success(response . message);
            $('#VairantChildModal').modal('hide');
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
