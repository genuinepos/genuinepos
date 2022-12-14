<form id="add_branch_form" action="{{ route('settings.branches.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.name') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control add_input" data-name="Name" id="name" placeholder="Business Location Name"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.location_code') :</strong> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Branch code must be unique." class="fas fa-info-circle tp"></i></label>
            <input type="text" name="code" class="form-control  add_input" data-name="Branch code" id="code" placeholder="@lang('menu.location_code')"/>
            <span class="error error_code"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.phone') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="@lang('menu.phone_number')"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.alternate_phone_number') :</strong> </label>
            <input type="text" name="alternate_phone_number" class="form-control " id="alternate_phone_number" placeholder="@lang('menu.alternate_phone_number')"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.city') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control  add_input" data-name="City" id="city" placeholder="@lang('menu.city')"/>
            <span class="error error_city"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.state') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control  add_input" data-name="State" id="state" placeholder="@lang('menu.state')"/>
            <span class="error error_state"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.country') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="country" class="form-control  add_input" data-name="country" id="country" placeholder="@lang('menu.country')"/>
            <span class="error error_country"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.zip_code') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control  add_input" data-name="Zip code" id="zip_code" placeholder="Zip code"/>
            <span class="error error_zip_code"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.email') :</strong> </label>
            <input type="text" name="email" class="form-control "  id="email" placeholder="Email address"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.website') :</strong> </label>
            <input type="text" name="website" class="form-control " id="website" placeholder="Website URL"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.logo') :</strong> <small class="text-danger">Logo size 200px * 70px</small> </label>
            <input type="file" name="logo" class="form-control " id="logo"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.add_sale_invoice_scheme') :</strong>  <span class="text-danger">*</span></label>
            <div class="input-group">
                <select name="invoice_schema_id" id="invoice_schema_id" data-name="invoice schema" class="form-control add_input">
                    <option value="">@lang('menu.select_please')</option>
                    @foreach ($invSchemas as $schema)
                        <option value="{{ $schema->id }}">{{ $schema->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-prepend">
                    <span class="input-group-text add_button" id="add_inv_schema" data-url="{{ route('settings.branches.quick.invoice.schema.modal') }}"
                        ><i class="fas fa-plus-square input_i"></i></span>
                </div>
            </div>
            <span class="error error_invoice_schema_id"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.add_sale_invoice_layout') :</strong>  <span class="text-danger">*</span></label>
            <select name="add_sale_invoice_layout_id" id="add_sale_invoice_layout_id" data-name="Add sale invoice layout" class="form-control  add_input">
                <option value="">@lang('menu.select_please')</option>
                @foreach ($invLayouts as $layout)
                    <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_add_sale_invoice_layout_id"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.pos_sale_invoice_layout') :</strong>  <span class="text-danger">*</span></label>
            <select name="pos_sale_invoice_layout_id" id="pos_sale_invoice_layout_id" data-name="POS sale invoice layout" class="form-control  add_input">
                <option value="">@lang('menu.select_please')</option>
                @foreach ($invLayouts as $layout)
                    <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_pos_sale_invoice_layout_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-12">
            <div class="row">
                <p class="checkbox_input_wrap mt-2">
            <input type="checkbox" name="purchase_permission" id="purchase_permission" value="1"> &nbsp; <b>@lang('menu.enable_purchase_permission')</b></p>
            </div>
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
                <label> <strong>@lang('menu.first_name') :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="@lang('menu.first_name')" data-name="First Name" autocomplete="off"/>
                <span class="error error_first_name"></span>
            </div>

            <div class="col-lg-3 col-md-6">
                <label> <strong>@lang('menu.last_name') :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="Last_name" class="form-control" id="Last_name" placeholder="@lang('menu.last_name')" autocomplete="off"/>
            </div>

            <div class="col-lg-3 col-md-6">
                <label> <strong>@lang('menu.phone') :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="user_phone" class="form-control" id="user_phone" placeholder="@lang('menu.phone_number')" data-name="Phone Number" autocomplete="off"/>
                <span class="error error_user_phone"></span>
            </div>

            <div class="col-lg-3 col-md-6">
                <label> <strong>{{ __('Role Permission') }} :</strong> <span class="text-danger">*</span> </label>
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
                <label> <strong>@lang('menu.username') :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="username" class="form-control" id="username" placeholder="@lang('menu.username')" autocomplete="off"/>
                <span class="error error_username"></span>
            </div>

            <div class="col-lg-3 col-md-6">
                <label> <strong>Password :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off"/>
                <span class="error error_password"></span>
            </div>

            <div class="col-lg-3 col-md-6">
                <label> <strong>@lang('menu.confirm_password') :</strong> <span class="text-danger">*</span> </label>
                <input type="text" name="password_confirmation" class="form-control" id="phone" placeholder="@lang('menu.confirm_password')" autocomplete="off"/>
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

<script>
    // Add branch by ajax
    $('#add_branch_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url:url,
            type:'post',
            data:new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data) {

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
                }else if (err.status == 500) {

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

    $('#add_opening_user_btn').on('click', function () {
        $('.add_opening_user_section').toggle(500);

        if ($('#add_opening_user').val() == '') {

            $('#add_opening_user').val(1)
        }else{

            $('#add_opening_user').val('')
        }
    });
</script>
