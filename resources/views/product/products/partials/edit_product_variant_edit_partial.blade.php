<hr class="p-0 m-0 my-1 mx-1">
<div class="row mt-3">
    <div class="col-md-6">
        <p class="fw-bold" style="background: #7dd9f8; display: inline; padding: 2px 7px;">{{ __("Create Variant") }}</p>
    </div>

    <div class="col-md-6">
        <div class="add_more_btn">
            <a href="#" id="add_more_variant_btn" class="btn btn-sm btn-success float-end">{{ __("Add More") }}</a>
        </div>
    </div>
    <div class="dynamic_variant_create_area">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive mt-1">
                    <table class="table modal-table table-sm">
                        <thead>
                            <tr class="text-center bg-primary variant_header">
                                <th class="text-white text-start">{{ __('Select Variant') }}</th>
                                <th class="text-white text-start">{{ __('Variant Code') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Also known as SKU. Variant code(SKU) must be unique.') }}" class="fas fa-info-circle tp"></i></th>
                                <th colspan="2" class="text-white text-start">{{ __('Unit Cost (Exc. Tax) & (Inc. Tax)') }}</th>
                                <th class="text-white text-start">{{ __('Profit(%)') }}</th>
                                <th class="text-white text-start">{{ __('Unit Price(Exc. Tax)') }}</th>
                                <th class="text-white text-start">{{ __('Variant Photo') }}</th>
                                <th><i class="fas fa-trash-alt text-white"></i></th>
                            </tr>
                        </thead>

                        <tbody class="dynamic_variant_body">
                            @php
                                $indexNumber = 0;
                            @endphp
                            @foreach ($product->variants as $variant)
                                <tr>
                                    <td class="text-start">
                                        <select class="form-control form-control" name="" id="variants">
                                            <option value="">{{ __('Create Combination') }}</option>
                                            @foreach ($bulkVariants as $bulkVariant)
                                                <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                            @endforeach
                                        </select>
                                        <input required type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" value="{{ $variant->variant_name }}" placeholder="{{ __('Variant Combination') }}">
                                        <input type="hidden" name="index_numbers[]" id="index_number" value="{{ $indexNumber }}">
                                        <input type="hidden" name="product_variant_ids[]" value="{{ $variant->id }}">
                                    </td>

                                    <td class="text-start">
                                        <input required type="text" name="variant_codes[]" class="form-control old_variant_code fw-bold" id="variant_code" value="{{ $variant->variant_code }}" placeholder="{{ __('Variant Code') }}">
                                    </td>

                                    <td class="text-start">
                                        <input required type="number" name="variant_costs_exc_tax[]" step="any" class="form-control fw-bold" value="{{ $variant->variant_cost }}" id="variant_cost_exc_tax" placeholder="{{ __('Variant Cost Exc. Tax') }}">
                                    </td>

                                    <td class="text-start">
                                        <input readonly required type="number" step="any" name="variant_costs_inc_tax[]" class="form-control fw-bold" id="variant_cost_inc_tax" value="{{ $variant->variant_cost_with_tax }}" placeholder="{{ __('Cost inc.tax') }}">
                                    </td>

                                    <td class="text-start">
                                        <input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" id="variant_profit" value="{{ $variant->variant_profit }}" placeholder="{{ __('Variant Profit Margin') }}">
                                    </td>

                                    <td class="text-start">
                                        <input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" id="variant_price_exc_tax" value="{{ $variant->variant_price }}" placeholder="{{ __('Price Exc. Tax') }}">
                                    </td>

                                    <td class="text-start">
                                        <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                    </td>

                                    <td class="text-start">
                                        @if (count($variant->productLedgers) == 0)
                                            <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                        @endif
                                    </td>
                                </tr>

                                @if ($product->has_multiple_unit == \App\Enums\BooleanType::True->value)
                                    <tr id="set_variant_multiple_units" class="set_variant_multiple_units">
                                        <td colspan="8" class="set_variant_multiple_units_td">
                                            <table class="table modal-table table-sm" id="set_variant_multiple_unit_table">
                                                <tr>
                                                    <th>{{ __('Unit') }}</th>
                                                    <th>{{ __('Unit Cost Exc. Tax') }}</th>
                                                    <th>{{ __('Unit Cost Inc. Tax') }}</th>
                                                    <th>{{ __('Unit Price Exc. Tax') }}</th>
                                                </tr>

                                                @foreach ($variant->variantUnits as $variantUnit)
                                                    <tr id="unit_table_row">
                                                        <td><span class="fw-bold base_unit_name">{{ $variantUnit?->assignedUnit?->name }}</span>
                                                            <input type="hidden" name="variant_base_unit_ids[{{ $indexNumber }}][]" id="variant_base_unit_id" value="{{ $variantUnit->base_unit_id }}">
                                                            <input type="hidden" name="variant_assigned_unit_quantities[{{ $indexNumber }}][]" id="variant_assigned_unit_quantity" value="{{ $variantUnit->assigned_unit_quantity }}">
                                                            <input type="hidden" name="variant_base_unit_multipliers[{{ $indexNumber }}][]" id="variant_base_unit_multiplier" value="{{ $variantUnit->base_unit_multiplier }}">
                                                            <input type="hidden" name="variant_assigned_unit_ids[{{ $indexNumber }}][]" id="variant_assigned_unit_id" value="{{ $variantUnit->assigned_unit_id }}">
                                                            <input type="hidden" name="product_variant_unit_ids[{{ $indexNumber }}][]" id="variant_assigned_unit_id" value="{{ $variantUnit->id }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="variant_assigned_unit_costs_exc_tax[{{
                                                                $indexNumber }}][]" class="form-control fw-bold" id="variant_assigned_unit_cost_exc_tax" value="{{ $variantUnit->unit_cost_exc_tax }}" placeholder="{{ __('Unit Cost Exc. Tax') }}">
                                                        </td>
                                                        <td>
                                                            <input readonly type="number" step="any" name="variant_assigned_unit_costs_inc_tax[{{ $indexNumber }}][]" class="form-control fw-bold" id="variant_assigned_unit_cost_inc_tax" value="{{ $variantUnit->unit_cost_inc_tax }}" placeholder="{{ __('Unit Cost Inc. Tax') }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="variant_assigned_unit_prices_exc_tax[{{ $indexNumber }}][]" class="form-control fw-bold" id="variant_assigned_unit_price_exc_tax" value="{{ $variantUnit->unit_price_exc_tax }}" placeholder="{{ __('Unit Price Exc. Tax') }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                @else
                                    <tr id="set_variant_multiple_units" class="set_variant_multiple_units"></tr>
                                @endif
                                @php
                                    $indexNumber++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
