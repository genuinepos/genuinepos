<form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
    <input type="hidden" name="id" id="id">
    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>Contact Type :</b>
            <select name="contact_type" class="form-control " id="e_contact_type">
                <option value="">Select contact type</option>
                <option value="1">Supplier</option>
                <option value="2">Customer</option>
                <option value="3">Both (Supplier - Customer)</option>
            </select>
        </div>

        <div class="col-md-3">
            <b>Supplier ID :</b>
            <input readonly type="text" name="contact_id" class="form-control"  placeholder="Contact ID" id="e_contact_id"/>
        </div>

        <div class="col-md-3">
            <b>Business Name :</b>
            <input type="text" name="business_name" class="form-control" placeholder="Business name" id="e_business_name"/>
        </div>

        <div class="col-md-3">
            <b>Name :</b>  <span class="text-danger">*</span>
            <input type="text" name="name" class="form-control edit_input" data-name="Supplier name" id="e_name" placeholder="Supplier name" />
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>Phone :</b><span class="text-danger">*</span>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="Phone number"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <b>Alternative Number :</b>
            <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number" id="e_alternative_phone"/>
        </div>

        <div class="col-md-3">
            <b>Landline :</b>
            <input type="text" name="landline" class="form-control " placeholder="landline number" id="e_landline"/>
        </div>

        <div class="col-md-3">
            <b>Email :</b>
            <input type="text" name="email" class="form-control " placeholder="Email address" id="e_email"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>Date Of Birth :</b> 
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="date" name="date_of_birth" class="form-control" autocomplete="off" id="e_date_of_birth">
            </div>
        </div>

        <div class="col-md-3">
            <b>Tax Number :</b>
            <input type="text" name="tax_number" class="form-control " placeholder="Tax number" id="e_tax_number"/>
        </div>

        <div class="col-md-3">
            <b>Pay Term :</b>
            <div class="col-md-12">
                <div class="row">
                    <input type="text" name="pay_term_number" class="form-control  w-50" id="e_pay_term_number"/>
                    <select name="pay_term" class="form-control  w-50" id="e_pay_term">
                        <option value="">Select term</option>
                        <option value="1">Days </option>
                        <option value="2">Months</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <b>Address :</b>
            <input type="text" name="address" class="form-control "  placeholder="Address" id="e_address">
        </div>

        <div class="col-md-3">
           <b>Prefix <i data-bs-toggle="tooltip" data-bs-placement="right" title="This prefix for barcode." class="fas fa-info-circle tp"></i> :</b> 
            <input type="text" name="prefix" id="e_prefix" class="form-control " placeholder="prefix"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
           <b>City :</b>  
            <input type="text" name="city" class="form-control " placeholder="City" id="e_city"/>
        </div>

        <div class="col-md-3">
           <b>State :</b>
            <input type="text" name="state" class="form-control " placeholder="State" id="e_state"/>
        </div>

        <div class="col-md-3">
            <b>Country :</b> 
            <input type="text" name="country" class="form-control " placeholder="Country" id="e_country"/>
        </div>

        <div class="col-md-3">
            <b>Zip-Code :</b> 
            <input type="text" name="zip_code" class="form-control " placeholder="zip_code" id="e_zip_code"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-5">
            <b>Shipping Address :</b> 
            <input type="text" name="shipping_address" class="form-control " placeholder="Shipping address" id="e_shipping_address"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end">Save Change</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>