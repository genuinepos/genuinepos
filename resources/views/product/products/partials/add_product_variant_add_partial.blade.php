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
                <tr>
                    <td class="text-start">
                        <select class="form-control form-control" name="" id="variants">
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
                        <input type="number" name="variant_costings[]" step="any" class="form-control requireable fw-bold" placeholder="{{ __("Cost") }}" id="variant_costing">
                    </td>

                    <td class="text-start">
                        <input type="number" step="any" name="variant_costings_with_tax[]" class="form-control requireable fw-bold" placeholder="{{ __("Cost inc.tax") }}" id="variant_costing_with_tax">
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
                            <input required type="number" name="variant_costings[]" step="any" class="form-control fw-bold" value="{{ $variant->variant_cost }}" id="variant_costing" placeholder="{{ __("Variant Cost Exc. Tax") }}">
                        </td>

                        <td class="text-start">
                            <input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control fw-bold" id="variant_costing_with_tax" value="{{ $variant->variant_cost_with_tax }}" placeholder="{{ __("Cost inc.tax") }}">
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
