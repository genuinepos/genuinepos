<div class="row gx-2 mt-1">
    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Unit Cost (Exc. Tax)') }}</b></label>
            <div class="col-8">
                <input type="number" step="any" name="product_cost" class="form-control fw-bold" id="product_cost" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <label class="col-4"><b>{{ __('Unit Cost (Inc. Tax)') }}</b></label>
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
            <label class="col-4"><b>{{ __('Has Multiple Unit?') }}</b> </label>
            <div class="col-8">
                <select name="has_multiple_unit" class="form-control" id="has_multiple_unit" data-next="type">
                    <option value="0">{{ __('No') }}</option>
                    <option value="1">{{ __('Yes') }}</option>
                </select>
            </div>
        </div>
    </div>

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
</div>

<div class="row mt-1">
    <div class="multi_unit_create_area d-hide">
        <hr class="p-0 m-0 my-1 mx-1">
        <div class="row align-items-end">

            <div class="col-md-6">
                <p class="fw-bold" style=" background: #6ce0cf; display: inline; padding: 2px 7px;">{{ __('Set Multiple Unit') }}</p>
            </div>

            <div class="col-md-6">
                <div class="add_more_btn">
                    <a href="#" id="add_more_unit_btn" class="btn btn-sm btn-success float-end">{{ __('Add More') }}</a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive mt-1">
                    <table class="table modal-table table-sm">
                        <thead>
                            <tr>
                                <th class="text-start">{{ __('By') }}</th>
                                <th class="text-start"></th>
                                <th class="text-start">{{ __('Quantity') }}</th>
                                <th class="text-start">{{ __('To') }}</th>
                                <th class="text-start">{{ __('Unit Cost (Exc. Tax)') }}</th>
                                <th class="text-start">{{ __('Unit Cost (Inc. Tax)') }}</th>
                                <th class="text-start">{{ __('Price (Exc. Tax)') }}</th>
                                <th><i class="fas fa-trash-alt text-white"></i></th>
                            </tr>
                        </thead>

                        <tbody id="multiple_unit_body">
                            @isset($defaultUnitId)
                                <tr>
                                    <td class="text-start" style="min-width: 100px;">
                                        <span id="span_base_unit_name" class="fw-bold base_unit_name">{{ __('1') }} {{ $defaultUnitName }}</span>
                                        <input type="hidden" name="base_unit_ids[]" id="base_unit_id" value="{{ $defaultUnitId }}">
                                    </td>

                                    <td class="text-start">
                                        <p class="fw-bold">X</p>
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="assigned_unit_quantities[]" class="form-control fw-bold multiple_unit_required_sometimes" id="assigned_unit_quantity" placeholder="{{ __('Quantity') }}">
                                        <input type="hidden" name="base_unit_multiplier" id="base_unit_multiplier">
                                    </td>

                                    <td class="text-start" style="min-width: 127px;">
                                        <div class="row align-items-end">
                                            <div class="col-md-2">
                                                <p class="fw-bold p-1">{{ __('1') }}</p>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="assigned_unit_ids[]" class="form-control assigned_unit_id multiple_unit_required_sometimes select2" id="assigned_unit_id" style="min-width: 110px !important;">
                                                    <option data-assigned_unit_name="" value="">{{ __('Unit') }}</option>
                                                    @foreach ($units as $unit)
                                                        <option data-assigned_unit_name="{{ $unit->name }}" value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="assigned_unit_costs_exc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_exc_tax" placeholder="{{ __('0.00') }}">
                                    </td>

                                    <td class="text-start">
                                        <input readonly type="number" step="any" name="assigned_unit_costs_inc_tax[]" class="form-control fw-bold" id="assigned_unit_cost_inc_tax" placeholder="{{ __('0.00') }}">
                                    </td>

                                    <td class="text-start">
                                        <input type="number" step="any" name="assigned_unit_prices_exc_tax[]" class="form-control fw-bold" id="assigned_unit_price_exc_tax" placeholder="{{ __('0.00') }}">
                                    </td>

                                    <td class="text-start">
                                        <a href="#" id="unit_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                    </td>
                                </tr>
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="dynamic_variant_create_area d-hide">
        <div class="row mt-1 align-items-end">
            <div class="col-md-6">
                <p class="fw-bold" style="background: #7dd9f8; display: inline; padding: 2px 7px;">{{ __('Create Variant') }}</p>
            </div>

            <div class="col-md-6">
                <div class="add_more_btn">
                    <a href="#" id="add_more_variant_btn" class="btn btn-sm btn-success float-end">{{ __('Add More') }}</a>
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
                                        <option value="">{{ __('Create Combination') }}</option>
                                        @foreach ($bulkVariants as $bulkVariant)
                                            <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="index_numbers[]" id="index_number" value="0">
                                    <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control reqireable fw-bold" placeholder="{{ __('Variant Combination') }}">
                                </td>

                                <td class="text-start">
                                    <input type="text" name="variant_codes[]" class="form-control reqireable fw-bold" id="variant_code" placeholder="{{ __('Variant Code') }}">
                                </td>

                                <td class="text-start">
                                    <input type="number" name="variant_costs_exc_tax[]" step="any" class="form-control requireable fw-bold" id="variant_cost_exc_tax" placeholder="{{ __('Unit Cost Exc. Tax') }}">
                                </td>

                                <td class="text-start">
                                    <input readonly type="number" step="any" name="variant_costs_inc_tax[]" class="form-control requireable fw-bold" id="variant_cost_inc_tax" placeholder="{{ __('Unit Cost Inc. tax') }}">
                                </td>

                                <td class="text-start">
                                    <input type="number" step="any" name="variant_profits[]" class="form-control requireable fw-bold" id="variant_profit" value="0.00" placeholder="{{ __('Profit') }}">
                                </td>

                                <td class="text-start">
                                    <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable fw-bold" id="variant_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}">
                                </td>

                                <td class="text-start">
                                    <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                </td>

                                <td class="text-start">
                                    <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                </td>
                            </tr>

                            <tr id="set_variant_multiple_units" class="set_variant_multiple_units">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
