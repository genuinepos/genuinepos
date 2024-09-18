<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Subcategory') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_sub_category_form" action="{{ route('subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label><b>{{ __('Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" value="{{ $subcategory->name }}" id="subcategory_name" data-next="subcategory_parent_category_id" placeholder="{{ __('Sub category name') }}" />
                    <span class="error error_subcategory_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __("Parent Category") }}</b> <span class="text-danger">*</span></label>
                    <select name="parent_category_id" class="form-control" id="subcategory_parent_category_id" data-next="subcategory_description">
                        @foreach ($categories as $row)
                            <option value="{{ $row->id }}" @if ($subcategory->parent_category_id == $row->id) selected @endif>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                    <span class="error error_sub_e_parent_category_id"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __('Description') }}</b></label>
                    <input name="description" class="form-control" id="subcategory_description" data-next="subcategory_save_changes" value="{{ $subcategory->description }}" placeholder="{{ __('Description') }}">
                </div>

                <div class="form-group editable_cate_img_field mt-1">
                    <label><b>{{ __('Photo') }} </b></label>
                    <input type="file" name="photo" class="form-control" id="subcategory_photo" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_subcategory_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button subcategory_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="subcategory_save_changes" class="btn btn-sm btn-success subcategory_submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.subcategory_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('click change keypress', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.subcategory_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_sub_category_form').on('submit', function(e) {
        e.preventDefault();

        $('.subcategory_loading_btn').show();
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
                $('.subcategory_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    $('#subcategoryAddOrEditModal').modal('hide');
                    subcategoriesTable.ajax.reload(null, false);
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.subcategory_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_subcategory_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
