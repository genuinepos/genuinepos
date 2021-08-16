  <!--begin::Form-->
  <form id="edit_branch_form" action="{{ route('settings.branches.update', $branch->id) }}">
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>Name :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Name" id="e_name" placeholder="Business Location Name" value="{{ $branch->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Location Code :</strong>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Branch code must be unique." class="fas fa-info-circle tp"></i></label>
            <input type="text" name="code" class="form-control edit_input" data-name="Branch code" id="e_code" placeholder="Business Location code" value="{{ $branch->branch_code }}"/>
            <span class="error error_e_code"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Phone :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="Phone number" value="{{ $branch->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label> <strong>City :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control edit_input" data-name="City" id="e_city" placeholder="City" value="{{ $branch->city }}"/>
            <span class="error error_e_city"></span>
        </div>

        <div class="col-md-4">
            <label><strong>State :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control edit_input" data-name="State" id="e_state" placeholder="State" value="{{ $branch->state }}"/>
            <span class="error error_e_state"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Country :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="country" class="form-control  edit_input" data-name="country" id="e_country" placeholder="Country" value="{{ $branch->country }}"/>
            <span class="error error_e_country"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label> <strong>Zip-code :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control edit_input" data-name="Zip code" id="e_zip_code" placeholder="Zip code" value="{{ $branch->zip_code }}"/>
            <span class="error error_e_zip_code"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Alternate Phone Number :</strong> </label>
            <input type="text" name="alternate_phone_number" class="form-control" id="e_alternate_phone_number" placeholder="Alternate phone number" value="{{ $branch->alternate_phone_number }}"/>
        </div>

        <div class="col-md-4">
            <label> <strong>Email :</strong> </label>
            <input type="text" name="email" class="form-control" id="e_email" placeholder="Email address" value="{{ $branch->email }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label> <strong>Website :</strong> </label>
            <input type="text" name="website" class="form-control" id="e_website" placeholder="Website URL" value="{{ $branch->website }}"/>
        </div>

        <div class="col-md-4">
            <label> <strong>Branch Logo :</strong> <small class="text-danger">Logo size 200px * 70px</small> </label>
            <input type="file" name="logo" class="form-control" id="logo"/>
        </div>

        <div class="col-md-4">
            <label><strong>Default Account :</strong> </label>
            <select name="default_account_id" id="e_default_account_id" class="form-control ">
                <option value="">Select Please</option>
                @foreach ($accounts as $ac)
                    <option {{ $ac->id == $branch->default_account_id ? 'SELECTED' : '' }} value="{{ $ac->id }}">{{ $ac->name.' (A/C:'.$ac->account_number.')' }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label><strong>Invoice Schema :</strong>  <span class="text-danger">*</span></label>
            <select name="invoice_schema_id" id="e_invoice_schema_id" data-name="Add sale pos invoice schema" class="form-control  edit_input">
                <option value="">Select Please</option>
                @foreach ($invSchemas as $schema)
                    <option {{ $schema->id == $branch->invoice_schema_id ? 'SELECTED' : '' }} value="{{ $schema->id }}">{{ $schema->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_invoice_schema_id"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Add Sale Invoice Layout :</strong>  <span class="text-danger">*</span></label>
            <select name="add_sale_invoice_layout_id" id="e_add_sale_invoice_layout_id" data-name="Add sale invoice layout" class="form-control  edit_input">
                <option value="">Select Please</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->add_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_add_sale_invoice_layout_id"></span>
        </div>

        <div class="col-md-4">
            <label><strong>POS Sale Invoice Layout :</strong>  <span class="text-danger">*</span></label>
            <select name="pos_sale_invoice_layout_id" id="e_pos_sale_invoice_layout_id" data-name="POS sale invoice layout" class="form-control  edit_input">
                <option value="">Select Please</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->pos_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_pos_sale_invoice_layout_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <div class="row">
                <p class="checkbox_input_wrap mt-2"> 
            <input type="checkbox" {{ $purchase_permission->id == 1 ? 'CHECKED' : '' }} name="purchase_permission" id="e_purchase_permission" value="1"> &nbsp; <b>Enable purchase permission</b>  </p> 
            </div>
        </div>
    </div>
 
    <div class="form-group text-end">
        <button type="button" class="btn loading_button d-none"><i
            class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
    <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
    </div>
</form>