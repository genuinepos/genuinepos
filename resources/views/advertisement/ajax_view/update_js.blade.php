 <script>
     //this is for select type video or url
     $(document).ready(function() {
         var initialContentType = '{{ $data->content_type ?? 0 }}';
         if (initialContentType == '1') {
             $('#img_attach').show();
             $('#video_attach').hide();
         } else if (initialContentType == '2') {
             $('#img_attach').hide();
             $('#video_attach').show();
         }

         $('#select_type').change(function() {
             var selectType = $(this).val();
             if (selectType == '1') {
                 $('#img_attach').show();
                 $('#video_attach').hide();
             } else if (selectType == '2') {
                 $('#img_attach').hide();
                 $('#video_attach').show();
             }
         });

         //this is for image append add more
         $("#addImage").click(function() {
             var newImageUpload = $('<div class="row image-upload mt-2 mb-2">' +
                 '<div class="col-md-4">' +
                 '<div class="form-group">' +
                 '<input type="file" name="image[]" class="form-control dropify" data-height="50" accept="" data-allowed-file-extensions="png jpeg jpg gif avif webp">' +
                 '</div>' +
                 '</div>' +
                 '<div class="col-md-4">' +
                 '<div class="form-group">' +
                 '<input type="text" name="content_title[]" class="form-control mt-2" placeholder="Enter Slider Title">' +
                 '</div>' +
                 '</div>' +
                 '<div class="col-md-4">' +
                 '<div class="form-group">' +
                 '<input type="text" name="caption[]" class="form-control mt-2" placeholder="Enter Slider Caption">' +
                 '</div>' +
                 '</div>' +
                 '<div class="col-md-12">' +
                 '<button type="button" class="btn btn-danger remove-image btn-sm">X</button>' +
                 '</div>' +
                 '</div>');
             $("#imageUploads").append(newImageUpload);
             $('.dropify').dropify();
         });

         //this is for add url append add more
         $("#addUrl").click(function() {
             var newUrlGroup = $('<div class="row url-upload"></div>');

             var newUrlColumn = $('<div class="col-md-6 mt-2 mb-2">' +
                 '<div class="form-group">' +
                 '<input type="file" name="url[]" class="form-control dropify" id="photo">' +
                 '</div>' +
                 '</div>');
             newUrlGroup.append(newUrlColumn);

             newUrlGroup.append('<div class="col-md-1 mt-2 mb-2">' +
                 '<button type="button" class="btn btn-danger remove-url btn-sm">X</button>' +
                 '</div>');
             $("#urlUploads").append(newUrlGroup);
         });


         //this is for remove image 
         $(document).on('click', '.remove-image', function() {
             $(this).closest('.image-upload').remove();
         });


         //this is for remove url 
         $(document).on('click', '.remove-url', function() {
             $(this).closest('.url-upload').remove();
         });

         $('.dropify').dropify();
     });


     $(document).ready(function() {
         $('#updateForm').submit(function(e) {
             e.preventDefault();
             var formData = new FormData(this);
             var url = $(this).attr('action');
             $.ajax({
                 url: url,
                 method: 'POST',
                 data: formData,
                 contentType: false,
                 processData: false,
                 success: function(response) {
                     if (response.status == 'error') {
                         toastr.error(response.message);
                         return false;
                     }
                      toastr.success(response.message);
                      setTimeout(function() {
                          location.reload();
                      }, 1000);
                     console.log(response);
                 },
                 error: function(xhr, status, error) {
                     var errors = xhr.responseJSON.errors;
                     console.log(error);
                     if (errors) {
                         $.each(errors, function(key, value) {
                             toastr.error(value[0]);
                             return false;
                         });
                     } else {
                         console.error(error);
                         alert('Error updating advertisement');
                     }
                 }
             });
         });




         $('.delete-item').click(function() {
             var itemId = $(this).data('id');
             if (confirm('Are you sure you want to delete this item?')) {
                 $.ajax({
                     url: "{{ route('advertise.destroy', '__itemId') }}".replace('__itemId', itemId),
                     type: 'DELETE',
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     success: function(response) {
                         toastr.success(response.message);
                         setTimeout(function() {
                             location.reload();
                         }, 1000);
                     },
                     error: function(xhr, status, error) {
                         // Handle error response
                         console.error(xhr.responseText);
                         alert('Failed to delete the item. Please try again.');
                     }
                 });
             }
         });








     });
 </script>