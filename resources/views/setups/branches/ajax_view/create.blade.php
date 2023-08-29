<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Add Shop") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_branch_form" action="{{ route('branches.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Shop Type") }}</b></label>
                        <select name="branch_type" class="form-control" id="branch_type" data-next="branch_name">
                            @foreach (\App\Enums\BranchType::cases() as $branchType)
                                <option value="{{ $branchType->value }}">{{ preg_replace("/[A-Z]/", ' ' . "$0", $branchType->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 parent_branches_field d-hide">
                        <label> <b>{{ __("Parent Shop") }}</b> <span class="text-danger">*</span></label>
                        <select name="parent_branch_id" class="form-control" id="branch_parent_branch_id" data-next="branch_code">
                            <option value="">{{ __('Select Parent Shop') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name.'/'.$branch->branch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6 branch_name_field">
                        <label><b>{{ __("Shop Name") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="branch_name" data-next="branch_area_name" placeholder="{{ __("Shop Name") }}"/>
                        <span class="error error_branch_name"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Area Name") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="area_name" class="form-control" id="branch_area_name" data-next="branch_code" placeholder="{{ __("Area Name") }}"/>
                        <span class="error error_branch_area_name"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Shop ID") }} </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="branch_code" class="form-control" id="branch_code" data-next="branch_phone" placeholder="{{ __("Shop ID") }}"/>
                        <span class="error error_branch_code"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Phone") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="phone" class="form-control" data-name="Phone number" id="branch_phone" data-next="branch_alternate_phone_number" placeholder="{{ __("Phone No") }}"/>
                        <span class="error error_branch_phone"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Alternative Phone") }}</b> </label>
                        <input type="text" name="alternate_phone_number" class="form-control" id="branch_alternate_phone_number" data-next="branch_country" placeholder="{{ __("Alternative Phone") }}"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Country") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="country" class="form-control" id="branch_country" data-next="branch_state" placeholder="{{ __("Country") }}"/>
                        <span class="error error_branch_country"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("State") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="state" class="form-control" id="branch_state" data-next="branch_city" placeholder="{{ __("State") }}"/>
                        <span class="error error_branch_state"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("City") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="city" class="form-control" id="branch_city" data-next="branch_zip_code" placeholder="{{ __("City") }}"/>
                        <span class="error error_branch_city"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Zip-Code") }}</b> <span class="text-danger">*</span></label>
                        <input required type="text" name="zip_code" class="form-control" id="branch_zip_code" data-next="branch_email" placeholder="Zip code"/>
                        <span class="error error_branch_zip_code"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Email") }}</b></label>
                        <input type="text" name="email" class="form-control" id="branch_email" data-next="branch_website" placeholder="{{ __("Email address") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Website") }}</b></label>
                        <input type="text" name="website" class="form-control" id="branch_website" data-next="branch_purchase_permission" placeholder="{{ __("Website Url") }}"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Logo") }}</b> <small class="text-danger">{{ __("Logo size 200px * 70px") }}</small></label>
                        <input type="file" name="logo" class="form-control " id="logo"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Is Enable Purchase") }}</label>
                        <select name="purchase_permission" class="form-control" id="branch_purchase_permission" data-next="add_initial_user_btn">
                            <option value="1">{{ __("Yes") }}</option>
                            <option value="0">{{ __("No") }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" value="1" class="btn text-white btn-sm btn-success float-center" id="add_initial_user_btn">
                            <i class="fas fa-user text-white"></i> {{ __("Add Initial User") }}</button>
                        <input type="hidden" name="add_initial_user" id="add_initial_user" value="">
                    </div>
                </div>

                <div class="add_initial_user_section" style="display: none;">
                    <div class="row mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __("First Name") }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="first_name" class="form-control initial_user_field" id="first_name" data-next="Last_name" placeholder="{{ __("First Name") }}" data-name="First Name" autocomplete="off" />
                            <span class="error error_first_name"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __("Last Name") }}</b></label>
                            <input type="text" name="Last_name" class="form-control" id="Last_name" data-next="user_phone" placeholder="{{ __("Last Name") }}" autocomplete="off" />
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __("Phone") }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_phone" class="form-control initial_user_field" id="user_phone" data-next="role_id" placeholder="{{ __("User Phone Number") }}" autocomplete="off" />
                            <span class="error error_user_phone"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __('Role Permission') }} </b> <span class="text-danger">*</span> </label>
                            <select name="role_id" id="role_id" class="form-control initial_user_field" data-next="username">
                                <option value="">{{ __('Select Role Permission') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <span class="error error_role_id"></span>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __("Username") }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="username" class="form-control initial_user_field" id="username" data-next="password" placeholder="{{ __("Username") }}" autocomplete="off" />
                            <span class="error error_username"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __("Password") }} </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password" class="form-control initial_user_field" id="password" data-next="password_confirmation" placeholder="{{ __("Password") }}" autocomplete="off" />
                            <span class="error error_password"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __("Confirm Password") }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password_confirmation" class="form-control" id="password_confirmation" data-next="branch_save" placeholder="{{ __("Confirm Password") }}" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-1">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        <button type="button" id="branch_save" class="btn btn-sm btn-success branch_submit_button">{{ __("Save") }}</button>
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

    $('#add_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.branch_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchAddOrEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload();
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;

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

    $('#add_initial_user_btn').on('click', function() {
        $('.add_initial_user_section').toggle(500);

        if ($('#add_initial_user').val() == '') {

            $('#add_initial_user').val(1);
            $('#first_name').focus();
            $('.initial_user_field').prop('require', true);
        } else {

            $('#add_initial_user').val('');
            $('#branch_save').focus();
            $('.initial_user_field').prop('require', false);
        }
    });

    $('#branch_type').on('click', function() {

        $('.parent_branches_field').hide();
        $('#parent_branch_id').val('');

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
