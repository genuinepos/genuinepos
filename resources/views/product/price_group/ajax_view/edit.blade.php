<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Selling Price Group") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_price_group_form" action="{{ route('selling.price.groups.update', $priceGroup->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <label><b>{{ __("Name") }}</b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="price_group_name" data-next="price_group_description" placeholder="{{ __("Price Group Name") }}" value="{{ $priceGroup->name }}"/>
                        <span class="error error_price_group_name"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <label><b>{{ __("Description") }}</b></label>
                        <input name="description" class="form-control" id="price_group_description" data-next="price_group_save_changes" placeholder="{{ __("Price Group Description") }}" value="{{ $priceGroup->description }}">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button price_group_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="button" id="price_group_save_changes" class="btn btn-sm btn-success price_group_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.price_group_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.price_group_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_price_group_form').on('submit',function(e) {
        e.preventDefault();

        $('.price_group_loading_btn').show();
        var url = $(this).attr('action');

        var request = $(this).serialize();

        $.ajax({
            url : url,
            type : 'post',
            data: request,
            success:function(data){

                $('.price_group_loading_btn').hide();

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                }

                toastr.success("{{ __('Selling Price Group is added successfully') }}");
                $('#priceGroupAddOrEditModal').modal('hide');
                priceGroupsTable.ajax.reload(null, false);
            }, error: function(err) {

                $('.price_group_loading_btn').hide();
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

                    $('.error_price_group_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
