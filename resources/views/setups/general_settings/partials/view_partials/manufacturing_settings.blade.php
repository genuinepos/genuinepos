<form id="manufacturing_settings_form" class="setting_form hide-all" action="{{ route('settings.manufacturing.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Manufacturing Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label class="fw-bold">{{ __('Production Voucher Prefix') }}</label>
            <input type="text" name="production_voucher_prefix" class="form-control" id="production_voucher_prefix" placeholder="{{ __('Product Voucher Prefix') }}" value="{{ $generalSettings['manufacturing__production_voucher_prefix'] }}" autocomplete="off">
        </div>

        <div class="col-md-6">
            <label class="fw-bold">{{ __('Enable Editing Ingredients Quantity In Production') }}</label>
            <select name="is_edit_ingredients_qty_in_production" class="form-control" id="is_edit_ingredients_qty_in_production">
                <option value="1">{{ __('Yes') }}</option>
                <option @selected($generalSettings['manufacturing__is_edit_ingredients_qty_in_production'] == 0) value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label class="fw-bold">{{ __('Update Product Cost And Selling Price Based On Net Cost') }}</strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Update Product Cost And Selling Price Based On Total Production Cost, On Finalizing Production') }}" class="fas fa-info-circle tp"></i></label>
            <select name="is_update_product_cost_and_price_in_production" class="form-control" id="is_update_product_cost_and_price_in_production">
                <option value="1">{{ __('Yes') }}</option>
                <option @selected($generalSettings['manufacturing__is_update_product_cost_and_price_in_production'] == 0) value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button manufacturing_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
