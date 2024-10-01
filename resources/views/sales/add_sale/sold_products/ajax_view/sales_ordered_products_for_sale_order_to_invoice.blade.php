<option value="">{{ __('Select Product') }}</option>
@foreach ($saleProducts as $orderedProduct)
    @php
        $brand = $orderedProduct?->product?->brand ? ' | ' . $orderedProduct?->product?->brand?->name : '';
    @endphp
    <option value="{{ $orderedProduct->id }}" data-name="{{ $orderedProduct?->product?->name . ($orderedProduct?->variant ? '-' . $orderedProduct?->variant?->name : '') . ' (' .$orderedProduct?->product?->product_code . ')' }}" data-p_id="{{ $orderedProduct->product_id }}" data-is_manage_stock="{{ $orderedProduct?->product?->is_manage_stock }}" data-v_id="{{ $orderedProduct?->variant_id }}" data-p_tax_ac_id="{{ $orderedProduct->tax_ac_id ? $orderedProduct->tax_ac_id : '' }}" data-tax_type="{{ $orderedProduct?->tax_type }}" data-is_show_emi_on_pos="{{ $orderedProduct?->product?->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $orderedProduct?->unit_price_exc_tax }}" data-p_discount="{{ $orderedProduct?->unit_discount }}" data-p_discount_type="{{ $orderedProduct?->unit_discount_type }}" data-p_discount_amount="{{ $orderedProduct?->unit_discount_amount }}" data-p_price_inc_tax="{{ $orderedProduct?->unit_price_inc_tax }}" data-p_cost_inc_tax="{{ $orderedProduct?->unit_cost_inc_tax }}"
        data-p_ordered_quantity="{{ $orderedProduct?->ordered_quantity }}" data-p_delivered_quantity="{{ $orderedProduct?->delivered_quantity }}" data-p_left_quantity="{{ $orderedProduct?->left_quantity }}">
        {{ $orderedProduct?->product?->name . ($orderedProduct?->variant ? '-' . $orderedProduct?->variant?->name : '') . ' (' .$orderedProduct?->product?->product_code . $brand . ')' }}
    </option>
@endforeach
