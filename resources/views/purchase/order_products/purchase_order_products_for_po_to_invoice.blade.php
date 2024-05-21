@php
    $itemUnitsArray = [];
@endphp
@foreach ($purchaseOrderProducts as $orderProduct)
    @php
        $variant = $orderProduct->variant ? ' - ' . $orderProduct->variant->variant_name : '';
        $variantId = $orderProduct->product_variant_id ? $orderProduct->product_variant_id : 'noid';

        if (isset($orderProduct->product_id)) {
            $itemUnitsArray[$orderProduct->product_id][] = [
                'unit_id' => $orderProduct->product->unit->id,
                'unit_name' => $orderProduct->product->unit->name,
                'unit_code_name' => $orderProduct->product->unit->code_name,
                'base_unit_multiplier' => 1,
                'multiplier_details' => '',
                'is_base_unit' => 1,
            ];
        }
    @endphp

    <tr id="select_product">
        <td>
            <span id="span_product_name">{{ $orderProduct->product->name . $variant }}</span>
            <input type="hidden" id="product_name" value="{{ $orderProduct->product->name . $variant }}">
            <input type="hidden" name="descriptions[]" id="description">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $orderProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
            <input type="hidden" name="purchase_order_product_ids[]" value="{{ $orderProduct->id }}">
            <input type="hidden" id="{{ $orderProduct->product_id . $variantId }}" value="{{ $orderProduct->product_id . $variantId }}">
        </td>

        <td>
            <span id="span_ordered_quantity_unit" class="fw-bold">{{ $orderProduct->ordered_quantity . '/' . $orderProduct?->unit?->name }}</span>
            <input type="hidden" id="ordered_quantity" value="{{ $orderProduct->ordered_quantity }}">
        </td>

        <td>
            <span id="span_pending_quantity_unit" class="fw-bold text-danger">{{ $orderProduct->pending_quantity . '/' . $orderProduct?->unit?->name }}</span>
            <input type="hidden" id="pending_quantity" value="{{ $orderProduct->pending_quantity }}">
        </td>

        <td>
            <span id="span_quantity_unit" class="fw-bold">{{ '0.00' . '/' . $orderProduct?->unit?->name }}</span>
            <input type="hidden" id="received_quantity" value="{{ $orderProduct->received_quantity }}">
            <input type="hidden" name="quantities[]" id="quantity" value="0.00">
            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $orderProduct->unit_id }}">
        </td>

        <td>
            <span id="span_unit_cost_exc_tax" class="fw-bold">{{ $orderProduct->unit_cost_exc_tax }}</span>
            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $orderProduct->unit_cost_exc_tax }}">
            <p class="p-0 m-0 fw-bold">{{ __('Batch No/Exprie Date') }}: <span id="span_batch_expire_date"></span></p>
            <input type="hidden" name="batch_numbers[]" id="batch_number">
            <input type="hidden" name="expire_dates[]" id="expire_date">
            <input type="hidden" id="has_batch_no_expire_date">
        </td>

        <td>
            <span id="span_discount_amount" class="fw-bold">{{ $orderProduct->unit_discount_amount }}</span>
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $orderProduct->unit_discount_type }}">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $orderProduct->unit_discount }}">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $orderProduct->unit_discount_amount }}">
            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $orderProduct->unit_cost_with_discount }}">
            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $orderProduct->subtotal }}">
        </td>

        <td>
            <span id="span_tax_percent" class="fw-bold">{{ $orderProduct->unit_tax_percent . '%' }}</span>
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $orderProduct->tax_ac_id }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $orderProduct->unit_tax_type }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $orderProduct->unit_tax_percent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $orderProduct->unit_tax_amount }}">
        </td>

        <td>
            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $orderProduct->net_unit_cost }}</span>
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $orderProduct->net_unit_cost }}">
            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ $orderProduct->net_unit_cost }}">
        </td>

        <td>
            <span id="span_linetotal" class="fw-bold">0.00</span>
            <input type="hidden" name="linetotals[]" id="linetotal" value="0.00">
        </td>

        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
            <td>
                <span id="span_profit" class="fw-bold">{{ $orderProduct->profit_margin }}</span>
                <input type="hidden" name="profits[]" id="profit" value="{{ $orderProduct->profit_margin }}">
            </td>

            <td>
                <span id="span_selling_price" class="fw-bold">{{ $orderProduct->selling_price }}</span>
                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ $orderProduct->selling_price }}">
            </td>
        @endif
    </tr>
@endforeach
