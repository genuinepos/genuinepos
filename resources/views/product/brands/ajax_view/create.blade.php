<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Brand") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_brand_form" action="{{ route('brands.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label><b>{{ __("Name") }}</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" data-next="brand_save" id="brand_name" placeholder="{{ __("Brand Name") }}"/>
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
                            <button type="submit" id="brand_save" class="btn btn-sm btn-success brand_submit_button">{{ __("Save") }}</button>
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
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_brand_form').on('submit',function(e) {
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

                    toastr.success("{{ __('Brand is added successfully') }}");
                    $('#brandAddOrEditModal').modal('hide');
                    var brand_id = $('#brand_id').val();
                    var product_brand_id = $('#product_brand_id').val();

                    if (brand_id != undefined) {

                        $('#brand_id').append('<option value="' + data.id + '">' + data.name +'</option>');
                        $('#brand_id').val(data.id);

                        var nextId = $('#brand_id').data('next');
                        $('#'+nextId).focus().select();
                    }else if (product_brand_id != undefined) {

                        $('#product_brand_id').append('<option value="' + data.id + '">' + data.name +'</option>');
                        $('#product_brand_id').val(data.id);

                        var nextId = $('#product_brand_id').data('next');
                        $('#'+nextId).focus().select();
                    } else {

                        brandsTable.ajax.reload();
                    }
                }
            }, error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.brand_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
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
