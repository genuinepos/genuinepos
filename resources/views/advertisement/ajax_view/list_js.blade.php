 <script>
      var productTable = $('.data_tbl').DataTable({
            // dom: "lBfrtip",
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('advertise.index') }}",
            },
            columns: [
                { data: 'content_type', name: 'content_type' },
                { data: 'title', name: 'title' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ],
        });


        $(document).ready(function() {
            $("#select_type").change(function(){
                var selectType = $(this).val();
                if (selectType == 1) {
                    $("#titleForm").show();
                    $("#urlForm").hide();
                    $("#imageUploads").show();
                } else {
                    $("#titleForm").hide();
                    $("#urlForm").show();
                    $("#imageUploads").hide();
                }
                console.log(selectType);
            });

            $("#addImage").click(function() {
                var newImageUpload = $('<div class="row image-upload">' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<input type="file" name="image[]" class="form-control dropify" data-height="50">' +
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
                                                '<button type="button" class="btn btn-danger remove-image btn-sm">Remove</button>' +
                                            '</div>' +
                                        '</div>');
                $("#imageUploads").append(newImageUpload); 
                $('.dropify').dropify(); 
            });

            $("#addUrl").click(function() {
                var newUrl = $('<div class="row url-upload">' +
                                    '<div class="col-md-12">' +
                                        '<div id="urlUploads">' +
                                            '<div class="form-group">' +
                                                '<input type="text" name="url[]" class="form-control" placeholder="URL">' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="col-md-8"></div>' +
                                    '<div class="col-md-12">' +
                                        '<button type="button" class="btn btn-danger remove-url btn-sm">Remove</button>' +
                                    '</div>' +
                            '</div>');
                $("#urlUploads").append(newUrl);
            });

            $(document).on('click', '.remove-image', function() {
                $(this).closest('.image-upload').remove();
            });

            $(document).on('click', '.remove-url', function() {
                $(this).closest('.url-upload').remove();
            });

            $('.dropify').dropify(); 
        });
</script>