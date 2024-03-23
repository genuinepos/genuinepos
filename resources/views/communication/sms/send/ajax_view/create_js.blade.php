<script>
$(document).on('submit', '#mail_send', function(event) {
    event.preventDefault();
  var formData = new FormData($(this)[0]);
    $.ajax({
        url: "{{ route('sms-send.store') }}",
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
           
      console.log(response.message);

            if(response.status=='error'){
                toastr.error(response.message);
                return false;
            }
            toastr . success(response . message);
            $('#VairantChildModal').modal('hide');
        },
            error: function(xhr, status, error) {

                console.log(status);
                var errorData = JSON.parse(xhr.responseText);
                
                var errorMessage = errorData.message || "An unknown error occurred.";
                
                // Accessing the status field
                var errorStatus = errorData.status;

                if (errorStatus === 'error') {
                    // Show error message if status is 'error'
                    toastr.error(errorMessage);
                } else {
                    // Handle other cases if needed
                    toastr.error('An error occurred: ' + errorMessage);
                }
            }

    });
});
</script>
