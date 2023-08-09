  <!--begin::Form-->
  <form id="edit_branch_form" action="{{ route('settings.branches.update', $branch->id) }}">
    @csrf
    <div class="form-group row">
        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.name') </strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Name" id="e_name" placeholder="Business Location Name" value="{{ $branch->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.location_code') </strong>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Branch code must be unique." class="fas fa-info-circle tp"></i></label>
            <input type="text" name="code" class="form-control edit_input" data-name="Branch code" id="e_code" placeholder="@lang('menu.location_code')" value="{{ $branch->branch_code }}"/>
            <span class="error error_e_code"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.phone') </strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="@lang('menu.phone_number')" value="{{ $branch->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.alternate_phone_number')</strong> </label>
            <input type="text" name="alternate_phone_number" class="form-control" id="e_alternate_phone_number" placeholder="@lang('menu.alternate_phone_number')" value="{{ $branch->alternate_phone_number }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.city') </strong> <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control edit_input" data-name="City" id="e_city" placeholder="@lang('menu.city')" value="{{ $branch->city }}"/>
            <span class="error error_e_city"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.state') </strong>  <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control edit_input" data-name="State" id="e_state" placeholder="@lang('menu.state')" value="{{ $branch->state }}"/>
            <span class="error error_e_state"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.country') </strong>  <span class="text-danger">*</span></label>
            <input type="text" name="country" class="form-control  edit_input" data-name="country" id="e_country" placeholder="@lang('menu.country')" value="{{ $branch->country }}"/>
            <span class="error error_e_country"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.zip_code') </strong> <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control edit_input" data-name="Zip code" id="e_zip_code" placeholder="Zip code" value="{{ $branch->zip_code }}"/>
            <span class="error error_e_zip_code"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.email') </strong> </label>
            <input type="text" name="email" class="form-control" id="e_email" placeholder="Email address" value="{{ $branch->email }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>@lang('menu.website') </strong> </label>
            <input type="text" name="website" class="form-control" id="e_website" placeholder="Website URL" value="{{ $branch->website }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label> <strong>{{ __('Location Logo') }} </strong> <small class="text-danger">Logo size 200px * 70px</small> </label>
            <input type="file" name="logo" class="form-control" id="logo"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.invoice_schema') </strong>  <span class="text-danger">*</span></label>
            <select name="invoice_schema_id" id="e_invoice_schema_id" data-name="Add sale pos invoice schema" class="form-control  edit_input">
                <option value="">@lang('menu.select_please')</option>
                @foreach ($invSchemas as $schema)
                    <option {{ $schema->id == $branch->invoice_schema_id ? 'SELECTED' : '' }} value="{{ $schema->id }}">{{ $schema->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_invoice_schema_id"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.add_sale_invoice_layout') </strong>  <span class="text-danger">*</span></label>
            <select name="add_sale_invoice_layout_id" id="e_add_sale_invoice_layout_id" data-name="Add sale invoice layout" class="form-control  edit_input">
                <option value="">@lang('menu.select_please')</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->add_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_add_sale_invoice_layout_id"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><strong>@lang('menu.pos_sale_invoice_layout') </strong>  <span class="text-danger">*</span></label>
            <select name="pos_sale_invoice_layout_id" id="e_pos_sale_invoice_layout_id" data-name="POS sale invoice layout" class="form-control  edit_input">
                <option value="">@lang('menu.select_please')</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->pos_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_pos_sale_invoice_layout_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <div class="row">
                <p class="checkbox_input_wrap mt-2">
            <input type="checkbox" {{ $branch->purchase_permission == 1 ? 'CHECKED' : '' }} name="purchase_permission" id="e_purchase_permission" value="1"> &nbsp; <b>@lang('menu.enable_purchase_permission')</b>  </p>
            </div>
        </div>
    </div>

    <div class="form-group d-flex justify-content-end">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
        </div>
    </div>
</form>

<script>
    // edit branch by ajax
    $('#edit_branch_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.edit_input');
            $('.error').html('');
            var countErrorField = 0;
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val()
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data:new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                $('#editModal').modal('hide');
                toastr.success(data);
                $('.loading_button').hide();
                getAllBranch();
            }
        });
    });
</script>
