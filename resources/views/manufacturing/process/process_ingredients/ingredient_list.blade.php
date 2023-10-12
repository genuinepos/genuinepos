@foreach ($ingredients as $ingredient)
    <tr class="text-start">
        <td>
            <span class="product_name">{{ $ingredient->product_name }}</span><br>
            <span class="product_variant">{{ $ingredient->variant_name }}</span>
            <input name="product_ids[]" type="hidden" class="productId-{{ $ingredient->product_id }}" id="product_id" value="{{ $ingredient->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $ingredient->variant_id ? $ingredient->variant_id : 'noid' }}">
            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $ingredient->unit_id }}">
            <input type="hidden" id="current_qty" value="0">
            <input type="hidden" step="any" data-unit="{{ $ingredient->unit_name }}" id="qty_limit" value="{{ $ingredient->stock }}">
        </td>

        <td>
            @if (isset($warehouse))
                {{ $warehouse->warehouse_name.'-('.$warehouse->warehouse_code.')-WH' }}
            @else
                {{ $branchName }}
            @endif
            <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ isset($warehouse) ? $warehouse->id : '' }}">
        </td>

        <td>
            <div class="input-group p-1">
                <input required type="number" name="input_quantities[]" class="form-control fw-bold" id="input_quantity" value="{{ $ingredient->final_qty }}">
                <input type="hidden" name="parameter_input_quantities[]" id="parameter_input_quantity" value="{{ $ingredient->final_qty }}" >
                <div class="input-group-prepend">
                    <span class="input-group-text input-group-text-custom">{{ $ingredient->unit_name }}</span>
                </div>
                  &nbsp;<strong><p class="text-danger m-0 p-0" id="input_qty_error"></p></strong>
            </div>
        </td>

        <td>
            <input required type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $ingredient->unit_cost_exc_tax }}">
            <input required type="hidden" name="tax_ac_ids[]" value="{{ $ingredient->tax_ac_id }}">
            <input required type="hidden" name="unit_tax_types[]" value="{{ $ingredient->unit_tax_type }}">
            <input required type="hidden" name="unit_tax_percents[]" value="{{ $ingredient->unit_tax_percent }}">
            <input required type="hidden" name="unit_tax_amounts[]" value="{{ $ingredient->unit_tax_amount }}">
            <input required  type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $ingredient->unit_cost_inc_tax }}">
            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $ingredient->unit_cost_inc_tax }}</span>
        </td>

        <td>
            <input value="{{ $ingredient->subtotal }}" type="hidden" step="any" name="subtotals[]" id="subtotal">
            <span id="span_subtotal" class="fw-bold">{{ $ingredient->subtotal }}</span>
        </td>
    </tr>
@endforeach
