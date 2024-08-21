@foreach ($saleProducts as $saleProduct)
    @php
        $variantName = $saleProduct?->variant ? ' - ' . $saleProduct?->variant?->variant_name : '';
        $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : 'noid';
    @endphp

    <tr id="select_item">
        <td class="text-start">
            <span class="product_name">{{ $saleProduct?->product?->name . $variantName }}</span>
            <input type="hidden" id="item_name" value="{{ $saleProduct?->product?->name . $variantName }}">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $saleProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $saleProduct->tax_ac_id != null ? $saleProduct->tax_ac_id : '' }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $saleProduct->tax_type }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $saleProduct->unit_tax_percent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $saleProduct->unit_tax_amount }}">
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $saleProduct->unit_discount_type }}">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $saleProduct->unit_discount }}">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $saleProduct->unit_discount_amount }}">
            <input type="hidden" name="sale_product_ids[]" value="{{ $saleProduct->id }}">
            <input type="hidden" name="sale_return_product_ids[]">
            <input type="hidden" class="unique_id" id="{{ $saleProduct->product_id . $saleProduct->branch_id . $saleProduct->warehouse_id . $variantId }}" value="{{ $saleProduct->product_id . $saleProduct->branch_id . $saleProduct->warehouse_id . $variantId }}">
        </td>

        <td class="text-start">
            <span id="span_unit_price_inc_tax" class="fw-bold">{{ $saleProduct->unit_price_inc_tax }}</span>
            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $saleProduct->unit_price_exc_tax }}">
            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $saleProduct->unit_cost_inc_tax }}">
        </td>

        <td class="text-start">
            <span id="span_sold_qty" class="fw-bold">
                {{ $saleProduct->quantity }}/{{ $saleProduct?->unit?->name }}
            </span>
            <input type="hidden" name="sold_quantities[]" value="{{ $saleProduct->quantity }}">
        </td>

        <td class="text-start">
            <span id="span_return_quantity" class="fw-bold">0.00</span>
            <input type="hidden" name="return_quantities[]" id="return_quantity" value="0.00">
            <input type="hidden" id="current_return_qty" value="0.00">
        </td>

        <td class="text text-start">
            <span id="span_unit" class="fw-bold">{{ $saleProduct?->unit?->name }}</span>
            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $saleProduct->unit_id }}">
        </td>

        <td class="text text-start">
            <span id="span_subtotal" class="fw-bold">0.00</span>
            <input type="hidden" name="subtotals[]" id="subtotal" value="0.00" tabindex="-1">
        </td>

        <td class="text-start">
            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@endforeach
