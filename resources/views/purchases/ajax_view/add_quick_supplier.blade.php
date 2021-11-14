<form id="add_supplier_form" action="{{ route('purchases.add.supplier') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>Name :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control s_add_input" data-name="Supplier name" id="name" placeholder="Supplier name"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>Supplier ID :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="Contact ID"/>
        </div>

        <div class="col-md-3">
            <label><strong>Business Name :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="Business name"/>
        </div>

        <div class="col-md-3">
            <label><strong>Phone :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control s_add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
            <span class="error error_phone"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>Alternative Number :</strong>  </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="Alternative phone number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Landline :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="landline number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Email :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="Email address"/>
        </div>

        <div class="col-md-3">
            <label><strong>Date Of Birth :</strong>  </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-picker" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>Tax Number :</strong>  </label>
            <input type="text" name="tax_number" class="form-control" placeholder="Tax number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Opening Balance :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="Opening balance"/>
        </div>

        <div class="col-md-3">
            <label><strong>Pay Term :</strong>  </label>
            <div class="col-md-12">
                <div class="row">
                    <input type="text" name="pay_term_number" class="form-control w-50"/>
                    <select name="pay_term" class="form-control w-50">
                        <option value="">Select term</option>
                        <option value="1">Days </option>
                        <option value="2">Months</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>Address :</strong></label>
            <input type="text" name="address" class="form-control"  placeholder="Address">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>City :</strong></label>
            <input type="text" name="city" class="form-control" placeholder="City"/>
        </div>

        <div class="col-md-3">
            <label><b>State :</b></label>
            <input type="text" name="state" class="form-control" placeholder="State"/>
        </div>

        <div class="col-md-3">
            <label><strong>Country :</strong></label>
            <input type="text" name="country" class="form-control" placeholder="Country"/>
        </div>

        <div class="col-md-3">
            <label><strong>Zip-Code :</strong></label>
            <input type="text" name="zip_code" class="form-control" placeholder="zip_code"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5">
            <label><strong>Shipping Address :</strong></label>
            <input type="text" name="shipping_address" class="form-control" placeholder="Shipping address"/>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>