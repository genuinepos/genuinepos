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
                        <select name="shop_type" class="form-control" id="shop_type">
                            <option value="1">{{ __("Different Shop") }}</option>
                            <option value="0">{{ __("Chain Shop") }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Shop Name") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="{{ __("Shop Name") }}" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Shop ID") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="branch_code" class="form-control" id="branch_code" placeholder="{{ __("Shop ID") }}"/>
                        <span class="error error_branch_code"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Phone") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" data-name="Phone number" id="phone" placeholder="{{ __("Phone No") }}" />
                        <span class="error error_phone"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Alternative Phone") }}</b> </label>
                        <input type="text" name="alternate_phone_number" class="form-control " id="alternate_phone_number" placeholder="@lang('menu.alternate_phone_number')"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("Country") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="country" class="form-control" data-name="country" id="country" placeholder="{{ __("Country") }}" />
                        <span class="error error_country"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label><b>{{ __("State") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control" data-name="State" id="state" placeholder="{{ __("State") }}" />
                        <span class="error error_state"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("City") }}</b> <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" data-name="City" id="city" placeholder="{{ __("City") }}" />
                        <span class="error error_city"></span>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Zip-Code") }} </b> <span class="text-danger">*</span></label>
                        <input type="text" name="zip_code" class="form-control" data-name="Zip code" id="zip_code" placeholder="Zip code" />
                        <span class="error error_zip_code"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Email") }} </b> </label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="{{ __("Email address") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Website") }} </b> </label>
                        <input type="text" name="website" class="form-control " id="website" placeholder="{{ __("Website Url") }}" />
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Logo") }} </b> <small class="text-danger">{{ __("Logo size 200px * 70px") }}</small> </label>
                        <input type="file" name="logo" class="form-control " id="logo" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-lg-3 col-md-6">
                        <label> <b>{{ __("Is Enable Purchase") }}</label>
                        <select name="is_enable_puble" class="form-control" id="is_enable_puble">
                            <option value="1">{{ __("Yes") }}</option>
                            <option value="0">{{ __("No") }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" value="1" class="btn text-white btn-sm btn-success float-center" id="add_opening_user_btn">
                            <i class="fas fa-user text-white"></i> @lang('menu.add_opening_user')</button>
                        <input type="hidden" name="add_opening_user" id="add_opening_user" value="">
                    </div>
                </div>

                <div class="add_opening_user_section" style="display: none;">
                    <div class="row mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label> <b>@lang('menu.first_name') </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="@lang('menu.first_name')" data-name="First Name" autocomplete="off" />
                            <span class="error error_first_name"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>@lang('menu.last_name') </b></label>
                            <input type="text" name="Last_name" class="form-control" id="Last_name" placeholder="@lang('menu.last_name')" autocomplete="off" />
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>@lang('menu.phone') </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_phone" class="form-control" id="user_phone" placeholder="@lang('menu.phone_number')" data-name="Phone Number" autocomplete="off" />
                            <span class="error error_user_phone"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __('Role Permission') }} </b> <span class="text-danger">*</span> </label>
                            <select name="role_id" id="role_id" class="form-control">
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
                            <label> <b>@lang('menu.username') </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="@lang('menu.username')" autocomplete="off" />
                            <span class="error error_username"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>Password </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off" />
                            <span class="error error_password"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>@lang('menu.confirm_password') </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password_confirmation" class="form-control" id="phone" placeholder="@lang('menu.confirm_password')" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-1">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger submit_button">@lang('menu.close')</button>
                        <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    // Add branch by ajax
    $('#add_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#addBranchModal').modal('hide');
                $('.submit_button').prop('type', 'sumbit');
                toastr.success(data);
                $('#add_branch_form')[0].reset();

                getAllBranch();
            },
            error: function(err) {

                $('.submit_button').prop('type', 'submit');
                $('.loading_button').hide();
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

    $('#add_opening_user_btn').on('click', function() {
        $('.add_opening_user_section').toggle(500);

        if ($('#add_opening_user').val() == '') {

            $('#add_opening_user').val(1)
        } else {

            $('#add_opening_user').val('')
        }
    });
</script>
