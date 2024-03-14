<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Category') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_category_form" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="category_name" data-next="category_description" placeholder="{{ __('Category name') }}" autofocus />
                    <span class="error error_category_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Description') }}</b></label>
                    <input name="description" class="form-control" id="category_description" data-next="category_save" placeholder="{{ __('Description') }}" autocomplete="off">
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Photo') }}</b><small class="text-danger"><b> ({{ __('size : 250px * 250px.') }})</b></small></label>
                    <input type="file" name="photo" class="form-control" id="category_photo">
                    <span class="error error_category_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button category_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="category_save" class="btn btn-sm btn-success category_submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.category_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.category_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.category_loading_btn').show();
        var url = $(this).attr('action');
        // var request = $(this).serialize();

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
                $('.category_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success("{{ __('Category created successfully') }}");
                    $('#categoryAddOrEditModal').modal('hide');
                    var category_id = $('#category_id').val();
                    var quick_product_category_id = $('#quick_product_category_id').val();

                    if (category_id != undefined) {

                        $('#category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#category_id').val(data.id);

                        var nextId = $('#category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (quick_product_category_id != undefined) {

                        $('#quick_product_category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#quick_product_category_id').val(data.id);

                        var nextId = $('#quick_product_category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        categoriesTable.ajax.reload();
                    }
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.category_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_category_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
