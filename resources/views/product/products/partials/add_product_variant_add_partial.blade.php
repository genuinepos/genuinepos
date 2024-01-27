<hr class="p-0 m-0 my-1 mx-1">
<div class="row align-items-end">

    <div class="col-md-6">
        <p class="fw-bold" style="background: #7dd9f8; display: inline; padding: 2px 7px;">{{ __("Create Variant") }}</p>
    </div>

    <div class="col-md-6">
        <div class="add_more_btn">
            <a href="#" id="add_more_variant_btn" class="btn btn-sm btn-primary float-end">{{ __("Add More") }}</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-responsive mt-1">
            <table class="table modal-table table-sm">
                <thead>
                    <tr class="text-center bg-primary variant_header">
                        <th class="text-white text-start">{{ __("Select Variant") }}</th>
                        <th class="text-white text-start">{{ __("Variant Code") }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Also known as SKU. Variant code(SKU) must be unique.") }}" class="fas fa-info-circle tp"></i></th>
                        <th colspan="2" class="text-white text-start">{{ __("Unit Cost (Exc. Tax) & (Inc. Tax)") }}</th>
                        <th class="text-white text-start">{{ __("Profit(%)") }}</th>
                        <th class="text-white text-start">{{ __("Unit Price(Exc. Tax)") }}</th>
                        <th class="text-white text-start">{{ __('Variant Photo') }}</th>
                        <th><i class="fas fa-trash-alt text-white"></i></th>
                    </tr>
                </thead>

                <tbody class="dynamic_variant_body">
                    @if (!isset($product))
                        <tr id="variant_row">
                            <td class="text-start">
                                <select class="form-control" name="" id="variants">
                                    <option value="">{{ __("Create Combination") }}</option>
                                    @foreach ($bulkVariants as $bulkVariant)
                                        <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control reqireable fw-bold" placeholder="{{ __("Variant Combination") }}">
                                <input type="hidden" name="index_numbers[]" id="index_number" value="0">
                            </td>

                            <td class="text-start">
                                <input type="text" name="variant_codes[]" id="variant_code" class="form-control reqireable fw-bold" placeholder="{{ __("Variant Code") }}">
                            </td>

                            <td class="text-start">
                                <input type="number" name="variant_costs_exc_tax[]" step="any" class="form-control requireable fw-bold" placeholder="{{ __("Cost Exc. Tax") }}" id="variant_cost_exc_tax">
                            </td>

                            <td class="text-start">
                                <input readonly type="number" step="any" name="variant_costs_inc_tax[]" class="form-control requireable fw-bold" placeholder="{{ __("Cost inc.tax") }}" id="variant_cost_inc_tax">
                            </td>

                            <td class="text-start">
                                <input type="number" step="any" name="variant_profits[]" class="form-control requireable fw-bold" placeholder="{{ __("Profit") }}" value="0.00" id="variant_profit">
                            </td>

                            <td class="text-start">
                                <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable fw-bold" placeholder="{{ __("Price Inc. Tax") }}" id="variant_price_exc_tax">
                            </td>

                            <td class="text-start">
                                <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                            </td>

                            <td class="text-start">
                                <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                            </td>
                        </tr>

                        <tr id="set_variant_multiple_units" class="set_variant_multiple_units">
                            {{-- <td colspan="8">
                                <table class="table modal-table table-sm" id="set_variant_multiple_unit_table">
                                    <tr>
                                        <th>{{ __("Unit") }}</th>
                                        <th>{{ __("Unit Cost Exc. Tax") }}</th>
                                        <th>{{ __("Unit Cost Inc. Tax") }}</th>
                                        <th>{{ __("Unit Price Exc. Tax") }}</th>
                                    </tr>
                                    <tr id="unit_table_row">
                                        <td>
                                            Leap
                                            <input type="hidden" name="variant_base_unit_ids[]" id="variant_base_unit_id">
                                            <input type="hidden" name="variant_assigned_unit_qunatities[]" id="variant_assigned_unit_qunatity">
                                            <input type="hidden" name="variant_base_unit_multipliers[]" id="variant_base_unit_multiplier">
                                            <input type="hidden" name="variant_assigned_unit_ids[]" id="variant_assigned_unit_id">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="variant_assigned_unit_costs_exc_tax[]" class="form-control" id="variant_assigned_unit_cost_exc_tax" placeholder="{{ __("Unit Cost Exc. Tax") }}">
                                        </td>
                                        <td>
                                            <input readonly type="number" step="any" name="variant_assigned_unit_costs_inc_tax[]" class="form-control" id="variant_assigned_unit_cost_inc_tax" placeholder="{{ __("Unit Cost Inc. Tax") }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" placeholder="{{ __("Unit Price Exc. Tax") }}">
                                        </td>
                                    </tr>
                                </table>
                            </td> --}}
                        </tr>
                    @elseif(isset($product) && count($product->variants) > 0)
                        @foreach ($product->variants as $variant)
                            <tr>
                                <td class="text-start">
                                    <select class="form-control form-control" name="" id="variants">
                                        <option value="">{{ __("Create Combination") }}</option>
                                        @foreach ($bulkVariants as $bulkVariant)
                                            <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                        @endforeach
                                    </select>
                                    <input required type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" value="{{ $variant->variant_name }}" placeholder="{{ __("Variant Combination") }}">
                                </td>

                                <td class="text-start">
                                    <input required type="text" name="variant_codes[]" id="variant_code" class="form-control old_variant_code fw-bold" value="{{ $variant->variant_code }}" placeholder="{{ __("Variant Code") }}">
                                </td>

                                <td class="text-start">
                                    <input required type="number" name="variant_costings[]" step="any" class="form-control fw-bold" value="{{ $variant->variant_cost }}" id="variant_costing" placeholder="{{ __("Cost Exc. Tax") }}">
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control fw-bold" id="variant_costing_with_tax" value="{{ $variant->variant_cost_with_tax }}" placeholder="{{ __("Cost inc. tax") }}">
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold"  id="variant_profit" value="{{ $variant->variant_profit }}" placeholder="{{ __("Variant Profit Margin") }}">
                                </td>

                                <td class="text-start">
                                    <input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" id="variant_price_exc_tax" value="{{ $variant->variant_price }}" placeholder="{{ __("Price Exc. Tax") }}">
                                </td>

                                <td class="text-start">
                                    <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                </td>

                                <td class="text-start">
                                    <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
