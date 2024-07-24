@foreach ($quotationProducts as $quotationProduct)
    <tr id="select_item">
        <td class="text-start">
            @php
                $variant = $quotationProduct->variant_id ? ' -' . $quotationProduct->variant->variant_name : '';
                $variantId = $quotationProduct->variant_id ? $quotationProduct->variant_id : 'noid';
            @endphp

            <span class="product_name">{{ $quotationProduct->product->name . $variant }} {!! $quotationProduct?->product?->is_manage_stock == 0 ? ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '' !!}</span>
            <input type="hidden" id="item_name" value="{{ $quotationProduct->product->name . $variant }}">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $quotationProduct->product_id }}">
            <input type="hidden" value="{{ $variantId }}" id="variant_id" name="variant_ids[]">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $quotationProduct->tax_type }}">
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $quotationProduct->tax_ac_id }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $quotationProduct->unit_tax_percent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $quotationProduct->unit_tax_amount }}">
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $quotationProduct->unit_discount_type }}">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $quotationProduct->unit_discount }}">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $quotationProduct->unit_discount_amount }}">
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $quotationProduct->unit_cost_inc_tax }}">

            <input type="hidden" class="unique_id" id="{{ $quotationProduct->product_id . $variantId }}" value="{{ $quotationProduct->product_id . $variantId }}">
        </td>

        <td class="text-start">
            <span id="span_quantity" class="fw-bold">{{ $quotationProduct->quantity }}</span>
            <input type="hidden" name="quantities[]" id="quantity" value="{{ $quotationProduct->quantity }}">
        </td>

        <td class="text-start">
            <span id="span_unit">{{ $quotationProduct?->unit?->name }}</span>
            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $quotationProduct?->unit?->id }}">
        </td>

        <td class="text-start">
            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $quotationProduct->unit_price_exc_tax }}">
            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $quotationProduct->unit_price_inc_tax }}">
            <span id="span_unit_price_inc_tax" class="fw-bold">{{ $quotationProduct->unit_price_inc_tax }}</span>
        </td>

        <td class="text-start">
            <strong><span id="span_subtotal">{{ $quotationProduct->subtotal }}</span></strong>
            <input type="hidden" value="{{ $quotationProduct->subtotal }}" readonly name="subtotals[]" id="subtotal">
        </td>

        <td class="text-start">
            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@endforeach
