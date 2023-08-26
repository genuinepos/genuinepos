<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Edit Shop") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_branch_form" action="{{ route('branches.update', $branch->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Shop Type") }}</b></label>
                        <select name="branch_type" class="form-control" id="branch_type" data-next="branch_name">
                            @foreach (\App\Enums\BranchType::cases() as $branchType)
                                <option {{ $branchType->value == $branch->branch_type ? 'SELECTED' : '' }} value="{{ $branchType->value }}">{{ preg_replace("/[A-Z]/", ' ' . "$0", $branchType->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 parent_branches_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'd-hide' }}">
                        <label> <b>{{ __("Parent Shop") }}</b> <span class="text-danger">*</span></label>
                        <select name="parent_branch_id" class="form-control" id="branch_parent_branch_id" data-next="branch_code">
                            <option value="">{{ __('Select Parent Shop') }}</option>
                            @foreach ($branches as $br)
                                <option {{ $br->id == $branch->parent_branch_id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name .' / '. $br->branch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6 branch_name_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}">
                        <label><b>{{ __("Shop Name") }} </b> <span class="text-danger">*</span></label>
                        <input {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'required' }} type="text" name="name" class="form-control" id="branch_name" data-next="branch_code" value="{{ $branch->name }}" placeholder="{{ __("Shop Name") }}" />
                        <span class="error error_branch_name"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Shop ID") }} </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="branch_code" class="form-control" id="branch_code" data-next="branch_phone" value="{{ $branch->branch_code }}" placeholder="{{ __("Shop ID") }}"/>
                        <span class="error error_branch_code"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Phone") }} </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="phone" class="form-control" data-name="Phone number" id="branch_phone" data-next="branch_alternate_phone_number" value="{{ $branch->phone }}" placeholder="{{ __("Phone No") }}" />
                        <span class="error error_branch_phone"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Alternative Phone") }}</b> </label>
                        <input type="text" name="alternate_phone_number" class="form-control" id="branch_alternate_phone_number" data-next="branch_country" value="{{ $branch->alternate_phone_number }}" placeholder="{{ __("Alternative Phone") }}"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Country") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="country" class="form-control" id="branch_country" data-next="branch_state" value="{{ $branch->country }}" placeholder="{{ __("Country") }}"/>
                        <span class="error error_branch_country"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("State") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="state" class="form-control" id="branch_state" data-next="branch_city" value="{{ $branch->state }}" placeholder="{{ __("State") }}" />
                        <span class="error error_branch_state"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("City") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="city" class="form-control" id="branch_city" data-next="branch_zip_code" value="{{ $branch->city }}" placeholder="{{ __("City") }}" />
                        <span class="error error_branch_city"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Zip-Code") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="zip_code" class="form-control" id="branch_zip_code" data-next="branch_email" value="{{ $branch->zip_code }}" placeholder="Zip code" />
                        <span class="error error_branch_zip_code"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Email") }}</b></label>
                        <input type="text" name="email" class="form-control" id="branch_email" data-next="branch_website" value="{{ $branch->email }}" placeholder="{{ __("Email address") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Website") }}</b></label>
                        <input type="text" name="website" class="form-control" id="branch_website" data-next="branch_purchase_permission" value="{{ $branch->website }}" placeholder="{{ __("Website Url") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Logo") }}</b> <small class="text-danger">{{ __("Logo size 200px * 70px") }}</small></label>
                        <input type="file" name="logo" class="form-control " id="logo"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Is Enable Purchase") }}</label>
                        <select name="purchase_permission" class="form-control" id="branch_purchase_permission" data-next="branch_save_changes">
                            <option value="1">{{ __("Yes") }}</option>
                            <option {{ $branch->purchase_permission == 0 ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-1">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="button" id="branch_save_changes" class="btn btn-sm btn-success branch_submit_button">{{ __("Save Changes") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.branch_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.branch_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.branch_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchAddOrEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload(false, null);
            },
            error: function(err) {

                $('.branch_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check all form fields.', 'Something Went Wrong');

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'branch_type' && $('#branch_type').val() == 2) {

                $('#branch_parent_branch_id').focus();
                return;
            }

            if ($(this).attr('id') == 'branch_parent_branch_id') {

                $('#branch_code').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $('#branch_type').on('click', function() {

        $('.parent_branches_field').hide();

        if ($(this).val() == 2) {

            $('.parent_branches_field').show();
            $('#branch_parent_branch_id').prop('required', true);
            $('.branch_name_field').hide();
            $('#branch_name').prop('required', false);
        } else {

            $('.parent_branches_field').hide();
            $('#branch_parent_branch_id').prop('required', false);
            $('.branch_name_field').show();
            $('#branch_name').prop('required', true);
        }
    });
</script>
