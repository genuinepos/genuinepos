 <script>
     // this is for datatable
     var productTable = $('.data_tbl').DataTable({
         // dom: "lBfrtip",
         "processing": true,
         "serverSide": true,
         aaSorting: [
             [0, 'asc']
         ],
         "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
         "lengthMenu": [
             [10, 25, 50, 100, 500, 1000, -1],
             [10, 25, 50, 100, 500, 1000, "All"]
         ],
         "ajax": {
             "url": "{{ route('advertise.index') }}",
         },
         columns: [{
                 data: 'content_type',
                 name: 'content_type'
             },
             {
                 data: 'title',
                 name: 'title'
             },
             {
                 data: 'attachment',
                 name: 'attachment'
             },
             {
                 data: 'status',
                 name: 'status'
             },
             {
                 data: 'action',
                 name: 'action'
             },
         ],
     });


     //this is for select type video or url
     $(document).ready(function() {
         $("#select_type").change(function() {
             var selectType = $(this).val();
             if (selectType == 1) {
                 $("#titleForm").show();
                 $("#urlForm").hide();
                 $("#imageUploads").show();
                 $('.url_form').hide();
             } else {
                 $("#titleForm").hide();
                 $('.image_form').hide();
                 $("#urlForm").show();
                 $("#imageUploads").hide();
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

     $(document).on('submit', '#add_data', function(event) {
         event.preventDefault();
         var formData = new FormData($(this)[0]);
         $.ajax({
             url: "{{ route('advertise.store') }}",
             type: 'POST',
             data: formData,
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             contentType: false,
             cache: false,
             processData: false,
             success: function(response) {
                 console.log(response);
                 if (response.status == 'error') {
                     toastr.error(response.message);
                     return false;
                 }
                 toastr.success(response.message);
                 setTimeout(function() {
                     location.reload();
                 }, 1000);
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