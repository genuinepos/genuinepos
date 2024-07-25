<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Subcategory') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_sub_category_form" action="{{ route('subcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mt-1">
                    <label><b>{{ __('Name') }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="subcategory_name" data-next="subcategory_parent_category" placeholder="Sub category name" />
                    <span class="error error_subcategory_name"></span>
                </div>

                @if (!$fixedParentCategory)
                    <div class="form-group">
                        <label><b>{{ __('Parent Category') }} <span class="text-danger">*</span></b></label>
                        <select required name="parent_category_id" class="form-control" id="subcategory_parent_category" data-next="subcategory_description">
                            <option selected="" disabled="">@lang('menu.select_parent_category')</option>
                            @foreach ($categories as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_subcategory_parent_category_id"></span>
                    </div>
                @else
                    <div class="form-group">
                        <label><b>{{ __('Parent Cagorygory') }} <span class="text-danger">*</span></b></label>
                        <select required name="parent_category_id" class="form-control" id="subcategory_parent_category" data-next="subcategory_description">
                            <option value="{{ $fixedParentCategory->id }}">{{ $fixedParentCategory->name }}</option>
                        </select>
                        <span class="error error_subcategory_parent_category_id"></span>
                    </div>
                @endif

                <div class="form-group mt-1">
                    <label><b>{{ __('Description') }}</b> </label>
                    <input name="description" class="form-control" id="subcategory_description" data-next="subcategory_save" placeholder="{{ __('Description') }}">
                </div>

                <div class="form-group mt-2">
                    <label><b>@lang('menu.sub_category_photo') </b></label>
                    <input type="file" name="photo" class="form-control " id="subcategory_photo" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_sub_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button subcategory_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="subcategory_save" class="btn btn-sm btn-success subcategory_submit_button">{{ __('Save') }}</button>
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
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_sub_category_form').on('submit', function(e) {
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

                    toastr.success("{{ __('Sub-cateogry created successfully') }}");
                    $('#subcategoryAddOrEditModal').modal('hide');
                    var sub_category_id = $('#sub_category_id').val();
                    var product_sub_category_id = $('#product_sub_category_id').val();

                    if (sub_category_id != undefined) {

                        $('#sub_category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#sub_category_id').val(data.id);

                        var nextId = $('#sub_category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else if (product_sub_category_id != undefined) {

                        $('#product_sub_category_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                        $('#product_sub_category_id').val(data.id);

                        var nextId = $('#product_sub_category_id').data('next');
                        $('#' + nextId).focus().select();
                    } else {

                        subcategoriesTable.ajax.reload();
                    }
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
