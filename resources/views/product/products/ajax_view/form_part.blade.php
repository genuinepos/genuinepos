<div class="row gx-2 mt-1">
    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Unit Cost(Exc. Tax)') }}</b></label>
            <div class="col-8">
                <input type="number" step="any" name="product_cost" class="form-control fw-bold" id="product_cost" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Unit Cost(Inc. Tax)') }}</b></label>
            <div class="col-8">
                <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control fw-bold" id="product_cost_with_tax" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
            </div>
        </div>
    </div>
</div>

<div class="row gx-2 mt-1">
    @if ($generalSettings['product__is_enable_price_tax'] == '1')
        <div class="col-md-6">
            <div class="input-group">
                <label class="col-4"><b> {{ __('Tax') }}</b></label>
                <div class="col-8">
                    <select class="form-control" name="tax_ac_id" id="tax_ac_id" data-next="tax_type">
                        <option data-tax_percent="0" value="">
                            @lang('menu.no_tax')</option>
                        @foreach ($taxAccounts as $tax)
                            <option data-tax_percent="{{ $tax->tax_percent }}" value="{{ $tax->id }}">{{ $tax->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
                <label class="col-4"><b>{{ __('Tax Type') }}</b> </label>
                <div class="col-8">
                    <select name="tax_type" class="form-control" id="tax_type" data-next="profit">
                        <option value="1">{{ __('Exclusive') }}</option>
                        <option value="2">{{ __('Inclusive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row gx-2 mt-1">
    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Profit Margin(%)') }}</b></label>
            <div class="col-8">
                <input type="number" step="any" name="profit" class="form-control fw-bold" id="profit" value="{{ $generalSettings['business_or_shop__default_profit'] > 0 ? $generalSettings['business_or_shop__default_profit'] : 0 }}" data-next="product_price" placeholder="0.00" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Unit Price(Exc. Tax)') }}</b></label>
            <div class="col-8">
                <input type="number" step="any" name="product_price" class="form-control fw-bold" id="product_price" data-next="is_variant" placeholder="0.00" autocomplete="off">
            </div>
        </div>
    </div>
</div>

<div class="row gx-2 mt-1">
    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Has Variant?') }}</b> </label>
            <div class="col-8">
                <select name="is_variant" class="form-control" id="is_variant" data-next="type">
                    <option value="0">{{ __('No') }}</option>
                    <option value="1">{{ __('Yes') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Thumbnail Photo') }}</b> </label>
            <div class="col-8">
                <input type="file" name="photo" class="form-control" id="photo">
                <span class="error error_photo"></span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="dynamic_variant_create_area d-hide">
        <div class="row">
            <div class="col-md-12">
                <div class="add_more_btn">
                    <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="#">@lang('menu.add_more')</a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive mt-1">
                    <table class="table modal-table table-sm">
                        <thead>
                            <tr class="text-center bg-primary variant_header">
                                <th class="text-white text-start">{{ __('Select Variant') }}</th>
                                <th class="text-white text-start">{{ __('Variant Code') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Also known as SKU. Variant code(SKU) must be unique.') }}" class="fas fa-info-circle tp"></i></th>
                                <th colspan="2" class="text-white text-start">{{ __('Unit Cost (Exc. Tax) & (Inc. Tax)') }}</th>
                                <th class="text-white text-start">{{ __('Profit(%)') }}</th>
                                <th class="text-white text-start">{{ __('Price Exc. Tax)') }}</th>
                                <th class="text-white text-start">{{ __('Variant Photo') }}</th>
                                <th><i class="fas fa-trash-alt text-white"></i></th>
                            </tr>
                        </thead>

                        <tbody class="dynamic_variant_body">
                            <tr>
                                <td class="text-start">
                                    <select class="form-control form-control" id="variants">
                                        <option value="">{{ __("Create Combination") }}</option>
                                        @foreach ($bulkVariants as $bulkVariant)
                                            <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control reqireable fw-bold" placeholder="{{ __("Variant Combination") }}">
                                </td>

                                <td class="text-start">
                                    <input type="text" name="variant_codes[]" id="variant_code" class="form-control reqireable fw-bold" placeholder="{{ __("Variant Code") }}">
                                </td>

                                <td class="text-start">
                                    <input type="number" name="variant_costings[]" step="any" class="form-control requireable fw-bold" placeholder="{{ __("Unit Cost Exc. Tax") }}" id="variant_costing">
                                </td>

                                <td class="text-start">
                                    <input type="number" step="any" name="variant_costings_with_tax[]" class="form-control requireable fw-bold" placeholder="{{ __("Unit Cost Inc. tax") }}" id="variant_costing_with_tax">
                                </td>

                                <td class="text-start">
                                    <input type="number" step="any" name="variant_profits[]" class="form-control requireable fw-bold" placeholder="{{ __("Profit") }}" value="0.00" id="variant_profit">
                                </td>

                                <td class="text-start">
                                    <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable fw-bold" placeholder="{{ __("Price Exc. Tax") }}" id="variant_price_exc_tax">
                                </td>

                                <td class="text-start">
                                    <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                </td>

                                <td class="text-start">
                                    <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
