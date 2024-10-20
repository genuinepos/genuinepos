<form id="product_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.product', $branch->id) }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Product Settings') }}</h6>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-3 col-sm-6">
            <label class="fw-bold">{{ __('Product Code Prefix(SKU)') }}</label>
            <input type="text" name="product_code_prefix" class="form-control" id="product_code_prefix" data-next="default_unit_id" value="{{ $generalSettings['product__product_code_prefix'] }}" autocomplete="off">
        </div>

        <div class="col-lg-3 col-sm-6">
            <label class="fw-bold">{{ __('Default Unit') }}</label>
            <select name="default_unit_id" class="form-control" id="default_unit_id" data-next="is_enable_brands">
                <option value="">{{ __('None') }}</option>
                @foreach ($units as $unit)
                    <option {{ $generalSettings['product__default_unit_id'] == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-4">
            <label class="fw-bold">{{ __('Enable Brands') }}</label>
            <select name="is_enable_brands" class="form-control" id="is_enable_brands" data-next="is_enable_categories">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['product__is_enable_brands'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-lg-3 col-md-4">
            <label class="fw-bold">{{ __('Enable Categories') }}</label>
            <select name="is_enable_categories" class="form-control" id="is_enable_categories" data-next="is_enable_sub_categories">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['product__is_enable_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-lg-3 col-md-4">
            <label class="fw-bold">{{ __('Enable Subcategories') }}</label>
            <select name="is_enable_sub_categories" class="form-control" id="is_enable_sub_categories" data-next="is_enable_price_tax">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['product__is_enable_sub_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-lg-3 col-md-4">
            <label class="fw-bold">{{ __('Enable Price Vat/Tax') }}</label>
            <select name="is_enable_price_tax" class="form-control" id="is_enable_price_tax" data-next="is_enable_warranty">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['product__is_enable_price_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-lg-3 col-md-4">
            <label class="fw-bold">{{ __('Enable Product Warranty') }}</label>
            <select name="is_enable_warranty" class="form-control" id="is_enable_warranty" data-next="is_show_other_stock_in_details">
                <option value="1">{{ __('Yes') }}</option>
                <option {{ $generalSettings['product__is_enable_warranty'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
            </select>
        </div>

        <div class="col-lg-6 col-md-6">
            <label class="fw-bold">{{ __('Display other location stock in product details.') }}</label>
            <select name="is_show_other_stock_in_details" class="form-control" id="is_show_other_stock_in_details" data-next="save_changes">
                <option value="1">{{ __('Yes') }}</option>
                <option @selected(isset($generalSettings['product__is_show_other_stock_in_details']) && $generalSettings['product__is_show_other_stock_in_details'] == '0') value="0">{{ __('No') }}</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button product_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
