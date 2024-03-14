<form id="purchase_settings_form" class="setting_form hide-all" action="{{ route('settings.purchase.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Purchase Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label class="fw-bold">{{ __('Enable Editing Product Price From Purchase Screen') }}</label>
            <select name="is_edit_pro_price" class="form-control" id="is_edit_pro_price" autofocus data-next="is_enable_lot_no">
                <option value="1">{{ __('Yes') }}</option>
                <option @selected($generalSettings['purchase__is_edit_pro_price'] == '0') value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="fw-bold">{{ __('Enable Lot Number') }}</label>
            <select name="is_enable_lot_no" class="form-control" id="is_enable_lot_no" data-next="save_changes_btn">
                <option value="1">{{ __('Yes') }}</option>
                <option @selected($generalSettings['purchase__is_enable_lot_no'] == '0') value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button purchase_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
