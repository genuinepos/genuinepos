<form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $supplier->id }}">
    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>Name :</b></label><span class="text-danger">*</span>
            <input type="text" name="name" class="form-control edit_input" data-name="Supplier name" id="e_name" placeholder="Supplier Name" value="{{ $supplier->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <b>Phone :</b> <span class="text-danger">*</span>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="Phone Number" value="{{ $supplier->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <b>Supplier ID :</b>
            <input readonly type="text" name="contact_id" class="form-control"  placeholder="Contact ID" id="e_contact_id" value="{{ $supplier->contact_id }}"/>
        </div>

        <div class="col-md-3">
            <b>Business Name :</b>
            <input type="text" name="business_name" class="form-control" placeholder="Business Name" id="e_business_name" value="{{ $supplier->business_name }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>Alternative Number :</b>
            <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative Phone Number" id="e_alternative_phone" value="{{ $supplier->alternative_phone }}"/>
        </div>

        <div class="col-md-3">
            <b>Landline :</b>
            <input type="text" name="landline" class="form-control " placeholder="Landline Number" id="e_landline" value="{{ $supplier->landline }}"/>
        </div>

        <div class="col-md-3">
            <b>Email :</b>
            <input type="text" name="email" class="form-control" placeholder="Email Address" id="e_email" value="{{ $supplier->email }}"/>
        </div>

        <div class="col-md-3">
            <b>Date Of Birth :</b>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-of-birth-picker" autocomplete="off" id="e_date_of_birth" value="{{ $supplier->date_of_birth }}" placeholder="YYYY-MM-DD">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <b>Tax Number :</b>
            <input type="text" name="tax_number" class="form-control " placeholder="Tax number" id="e_tax_number" value="{{ $supplier->tax_number }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.opening_balance') :</b></label>
            <input type="text" name="opening_balance" class="form-control " placeholder="@lang('menu.opening_balance')" id="e_opening_balance" value="{{ $branchOpeningBalance ? $branchOpeningBalance->amount : 0.00 }}"/>
        </div>

        <div class="col-md-3">
            <label><b>Pay Term</b> : </label>
            <div class="row">
                <div class="col-md-5">
                    <input type="number" step="any" name="pay_term_number" class="form-control"
                    id="e_pay_term_number" value="{{ $supplier->pay_term_number }}" placeholder="Number"/>
                </div>

                <div class="col-md-7">
                    <select name="pay_term" class="form-control">
                        <option value="">Days/Months</option>
                        <option {{ $supplier->pay_term == 1 ? 'SELECTED' : '' }} value="1">Days</option>
                        <option {{ $supplier->pay_term == 2 ? 'SELECTED' : '' }} value="2">Months</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <b>Address :</b>
            <input type="text" name="address" class="form-control" placeholder="Address" id="e_address" value="{{ $supplier->address }}">
        </div>

        <div class="col-md-3">
           <b>Prefix :</b>
            <input readonly type="text" name="prefix" id="e_prefix" class="form-control " placeholder="prefix" value="{{ $supplier->prefix }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
           <b>City :</b>
            <input type="text" name="city" class="form-control " placeholder="City" id="e_city" value="{{ $supplier->city }}"/>
        </div>

        <div class="col-md-3">
           <b>State :</b>
            <input type="text" name="state" class="form-control " placeholder="State" id="e_state" value="{{ $supplier->state }}"/>
        </div>

        <div class="col-md-3">
            <b>Country :</b>
            <input type="text" name="country" class="form-control " placeholder="Country" id="e_country" value="{{ $supplier->country }}"/>
        </div>

        <div class="col-md-3">
            <b>Zip-Code :</b>
            <input type="text" name="zip_code" class="form-control " placeholder="Zip-Code" id="e_zip_code" value="{{ $supplier->zip_code }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-5">
            <b>Shipping Address :</b>
            <input type="text" name="shipping_address" class="form-control " placeholder="Shipping address" id="e_shipping_address" value="{{ $supplier->shipping_address }}"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                <button type="submit" class="btn btn-sm btn-success">Save Change</button>
            </div>
        </div>
    </div>
</form>
