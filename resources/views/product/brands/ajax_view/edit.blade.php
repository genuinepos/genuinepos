<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Brand") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_brand_form" action="{{ route('brands.update', $brand->id) }}">
                @csrf
                <div class="form-group">
                    <label><b>{{ __("Name") }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" value="{{ $brand->name}}" id="brand_name" data-next="brand_save_changes" placeholder="{{ __("Brand Name") }}"/>
                    <span class="error error_brand_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><b>{{ __("Photo") }}</b></label>
                    <input type="file" name="photo" class="form-control" data-max-file-size="2M" id="brand_photo" accept=".jpg, .jpeg, .png, .gif">
                    <span class="error error_brand_photo"></span>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button brand_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="brand_save_changes" class="btn btn-sm btn-success brand_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.brand_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.brand_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_brand_form').on('submit',function(e) {
        e.preventDefault();

        $('.brand_loading_btn').show();
        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function(){
                isAjaxIn = true;
            },
            url : url,
            type : 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success:function(data){

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.brand_loading_btn').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                }else{

                    toastr.success(data);
                    $('#brandAddOrEditModal').modal('hide');
                    brandsTable.ajax.reload(null, false);
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.brand_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if(err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if(err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_brand_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });
</script>
