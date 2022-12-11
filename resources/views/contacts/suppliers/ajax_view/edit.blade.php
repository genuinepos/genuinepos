<form id="edit_supplier_form" action="{{ route('contacts.supplier.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $supplier->id }}">
    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <label><b>@lang('menu.name') :</b></label><span class="text-danger">*</span>
            <input type="text" name="name" class="form-control edit_input" data-name="Supplier name" id="e_name" placeholder="@lang('menu.supplier_name')" value="{{ $supplier->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.phone') :</b> <span class="text-danger">*</span>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="@lang('menu.phone_number')" value="{{ $supplier->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.supplier_id') :</b>
            <input readonly type="text" name="contact_id" class="form-control"  placeholder="Contact ID" id="e_contact_id" value="{{ $supplier->contact_id }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.business_name') :</b>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('menu.business_name')" id="e_business_name" value="{{ $supplier->business_name }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.alternative_number') :</b>
            <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative Phone Number" id="e_alternative_phone" value="{{ $supplier->alternative_phone }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.landline') :</b>
            <input type="text" name="landline" class="form-control " placeholder="Landline Number" id="e_landline" value="{{ $supplier->landline }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.email') :</b>
            <input type="text" name="email" class="form-control" placeholder="Email Address" id="e_email" value="{{ $supplier->email }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.date_of_birth'):</b>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-of-birth-picker" autocomplete="off" id="e_date_of_birth" value="{{ $supplier->date_of_birth }}" placeholder="YYYY-MM-DD">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.tax_number') :</b>
            <input type="text" name="tax_number" class="form-control " placeholder="@lang('menu.tax_number')" id="e_tax_number" value="{{ $supplier->tax_number }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><b>@lang('menu.opening_balance') :</b></label>
            <input type="text" name="opening_balance" class="form-control " placeholder="@lang('menu.opening_balance')" id="e_opening_balance" value="{{ $branchOpeningBalance ? $branchOpeningBalance->amount : 0.00 }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <label><b>@lang('menu.pay_term')</b> : </label>
            <div class="row">
                <div class="col-md-5">
                    <input type="number" step="any" name="pay_term_number" class="form-control"
                    id="e_pay_term_number" value="{{ $supplier->pay_term_number }}" placeholder="Number"/>
                </div>

                <div class="col-md-7">
                    <select name="pay_term" class="form-control">
                        <option value="">@lang('menu.days')/@lang('menu.months')</option>
                        <option {{ $supplier->pay_term == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.days')</option>
                        <option {{ $supplier->pay_term == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.months')</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <b>@lang('menu.address') :</b>
            <input type="text" name="address" class="form-control" placeholder="Address" id="e_address" value="{{ $supplier->address }}">
        </div>

        <div class="col-md-3">
           <b>@lang('menu.prefix') :</b>
            <input readonly type="text" name="prefix" id="e_prefix" class="form-control " placeholder="prefix" value="{{ $supplier->prefix }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-6">
           <b>@lang('menu.city') :</b>
            <input type="text" name="city" class="form-control " placeholder="@lang('menu.city')" id="e_city" value="{{ $supplier->city }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
           <b>@lang('menu.state') :</b>
            <input type="text" name="state" class="form-control " placeholder="@lang('menu.state')" id="e_state" value="{{ $supplier->state }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.country') :</b>
            <input type="text" name="country" class="form-control " placeholder="@lang('menu.country')" id="e_country" value="{{ $supplier->country }}"/>
        </div>

        <div class="col-lg-3 col-md-6">
            <b>@lang('menu.zip_code') :</b>
            <input type="text" name="zip_code" class="form-control " placeholder="@lang('menu.zip_code')" id="e_zip_code" value="{{ $supplier->zip_code }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-5">
            <b>@lang('menu.shipping_address') :</b>
            <input type="text" name="shipping_address" class="form-control " placeholder="@lang('menu.shipping_address')" id="e_shipping_address" value="{{ $supplier->shipping_address }}"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_change')</button>
            </div>
        </div>
    </div>
</form>
