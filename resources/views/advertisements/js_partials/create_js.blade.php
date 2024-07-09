<script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
<script>
    //this is for select type video or url
    $(document).ready(function() {
        $('.dropify').dropify();

        $("#content_type").change(function() {

            var contentType = $(this).val();
            if (contentType == 1) {

                $("#image_upload_area").show();
                $("#video_upload_area").hide();
            } else {

                $("#image_upload_area").hide();
                $("#video_upload_area").show();
            }
        });

        //this is for image append add more
        $("#addImage").click(function() {

            var newImage = $(
                '<tr class="more_image">' +
                '<td>' +
                '<input type="file" name="images[]" class="form-control dropify" id="image" data-allowed-file-extensions="png jpeg jpg gif avif webp">' +
                '</td>' +

                '<td>' +
                '<input type="text" name="content_titles[]" class="form-control" placeholder="' + "{{ __('Enter Slider Title') }}" + '">' +
                '</td>' +

                '<td>' +
                '<input type="text" name="captions[]" class="form-control" placeholder="' + "{{ __('Enter Slider Caption') }}" + '">' +
                '</td>' +

                '<td>' +
                '<button type="button" class="btn btn-danger btn-sm delete-item" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' +
                '</td>' +
                '</tr>'
            );

            $('.dropify').dropify();
            $("#advertisement_images").prepend(newImage);
        });

        //this is for remove image
        $(document).on('click', '#remove_image', function() {
            $(this).closest('tr').remove();
        });
    });

    var isAllowSubmit = true;
    $(document).on('click', '.advertisement_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_advertisement_form').on('submit', function(e) {
        e.preventDefault();

        $('.advertisement_loading_btn').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.advertisement_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                $('#add_advertisement_form')[0].reset();
                $(".more_image").remove();
                $(".dropify-clear").click();
                $("#image_upload_area").hide();
                $("#video_upload_area").hide();

                toastr.success(data);
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.advertisement_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
