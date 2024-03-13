<form id="add_sale_settings_form" class="setting_form hide-all" action="{{ route('settings.add.sale.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Add Sale Settings') }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Default Sale Discount') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_f"></i></span>
                </div>
                <input type="text" name="default_sale_discount" class="form-control" id="default_sale_discount" autocomplete="off" value="{{ $generalSettings['add_sale__default_sale_discount'] }}" data-next="sales_commission" autofocus>
            </div>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Default Selling Price Group') }}</label>
            <select name="default_price_group_id" class="form-control" id="default_price_group_id" data-next="save_changes">
                <option value="null">{{ __('None') }}</option>
                @foreach ($priceGroups as $priceGroup)
                    <option @selected($generalSettings['add_sale__default_price_group_id'] == $priceGroup->id) value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Add Sale Default Tax') }}</label>
            <select class="form-control" name="default_tax_ac_id" id="add_sale_default_tax_ac_id" data-next="is_show_recent_transactions">
                <option value="">{{ __('None') }}</option>
                @foreach ($taxAccounts as $tax)
                    <option @selected($generalSettings['add_sale__default_tax_ac_id'] == $tax->id) value="{{ $tax->id }}">{{ $tax->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button add_sale_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
