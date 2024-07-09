<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>
    //this is for select type video or url
    $(document).ready(function() {
        $('.dropify').dropify();
        //this is for image append add more
        $("#addImage").click(function() {
            var newImage = $(
                '<tr>' +
                '<td>' +
                '<input type="hidden" name="attachment_ids[]">' +
                '<input type="file" name="images[]" class="form-control dropify" id="image" data-allowed-file-extensions="png jpeg jpg gif avif webp">' +
                '</td>' +

                '<td>' +
                '<input type="text" name="content_titles[]" class="form-control" placeholder="' + "{{ __('Enter Slider Title') }}" + '">' +
                '</td>' +

                '<td>' +
                '<input type="text" name="captions[]" class="form-control" placeholder="' + "{{ __('Enter Slider Caption') }}" + '">' +
                '</td>' +

                '<td>' +
                '<button type="button" class="btn btn-danger btn-sm" id="remove_image"><i class="fa fa-trash"></i></button>' +
                '</td>' +
                '</tr>'
            );

            $("#advertisement_images").prepend(newImage);
            $('.dropify').dropify();
        });

        //this is for remove image
        $(document).on('click', '#remove_image', function() {
            $(this).closest('tr').remove();
        });
    });

    $(document).ready(function() {

        var isAllowSubmit = true;
        $(document).on('click', '.advertisement_submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        $('#edit_advertisement_form').on('submit', function(e) {
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
    });
</script>
