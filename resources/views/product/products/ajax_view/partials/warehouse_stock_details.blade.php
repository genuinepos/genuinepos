@if ($product->is_variant == 0)
    <table id="single_product_warehouse_stock_table" class="table modal-table table-sm">
        <thead>
            <tr class="bg-primary">
                <th class="text-white text-start">Product Code(SKU)</th>
                <th class="text-white text-start">Product</th>
                <th class="text-white text-start">Warehouse</th>
                <th class="text-white text-start">Branch</th>
                <th class="text-white text-start">Unit Price (Inc.Tax)</th>
                <th class="text-white text-start">Current Stock</th>
                <th class="text-white text-start">Stock Value</th>
            </tr>
        </thead>
        <tbody>
            @if (count($product->product_warehouses) > 0)
                @foreach ($product->product_warehouses as $product_warehouse)
                    <tr>
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $product_price_inc_tax = ($product->product_price / 100) * $tax + $product->product_price;
                            if ($product->tax_type == 2) {
                                $inclusiveTax = 100 + $tax;
                                $calc = ($product->product_price / $inclusiveTax) * 100;
                                $__tax_amount = $product->product_price - $calc;
                                $product_price_inc_tax = $product->product_price + $__tax_amount;
                            }
                        @endphp
                        <td class="text-start">{{ $product->product_code }}</td>
                        <td class="text-start">{{ $product->name }}</td>
                        <td class="text-start">{{ $product_warehouse->warehouse->warehouse_name .'/'. $product_warehouse->warehouse->warehouse_code }}
                        </td>
                        <td class="text-start">
                            <b>
                                @if ($product_warehouse->warehouse->branch)
                                    {{ $product_warehouse->warehouse->branch->name . '/' . $product_warehouse->warehouse->branch->branch_code }}
                                @else 
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}(Head Office)
                                @endif
                            </b>
                        </td>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($product_price_inc_tax, 0, 2) }}
                        </td>
                        <td class="text-start">{{ $product_warehouse->product_quantity . ' (' . $product->unit->code_name . ')' }}
                        </td>
                        @php
                            $stockValue = $product_warehouse->product_quantity * $product_price_inc_tax;
                        @endphp
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($stockValue, 0, 2) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any
                        warehouse.</td>
                </tr>
            @endif
        </tbody>
    </table>
@else
    <table id="variant_product_warehouse_stock_table" class="table modal-table table-sm">
        <thead>
            <tr class="bg-primary">
                <th class="text-white text-start">Product Code(SKU)</th>
                <th class="text-white text-start">Product</th>
                <th class="text-white text-start">Warehouse</th>
                <th class="text-white text-start">Branch</th>
                <th class="text-white text-start">Unit Price (Inc.Tax)</th>
                <th class="text-white text-start">Current Stock</th>
                <th class="text-white text-start">Stock Value</th>
            </tr>
        </thead>
        <tbody>
            @if (count($product->product_warehouses) > 0)
                @foreach ($product->product_warehouses as $product_warehouse)
                    @foreach ($product_warehouse->product_warehouse_variants as $product_warehouse_variant)
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $variant_price_inc_tax = ($product_warehouse_variant->product_variant->variant_price / 100) * $tax + $product_warehouse_variant->product_variant->variant_price;
                            if ($product->tax_type == 2) {
                                $inclusiveTax = 100 + $tax;
                                $calc = ($product_warehouse_variant->product_variant->variant_price / $inclusiveTax) * 100;
                                $__tax_amount = $product_warehouse_variant->product_variant->variant_price - $calc;
                                $variant_price_inc_tax = $product_warehouse_variant->product_variant->variant_price + $__tax_amount;
                            }
                        @endphp
                        <tr>
                            <td class="text-start">{{ $product_warehouse_variant->product_variant->variant_code }}
                            </td>
                            <td class="text-start">{{ $product->name . ' - ' . $product_warehouse_variant->product_variant->variant_name }}
                            </td>
                            <td class="text-start">
                            <b>{{ $product_warehouse->warehouse->warehouse_name .'/'. $product_warehouse->warehouse->warehouse_code }}</b> 
                            </td>
                            <td class="text-start">
                                <b>
                                    @if ($product_warehouse->warehouse->branch)
                                        {{ $product_warehouse->warehouse->branch->name . '/' . $product_warehouse->warehouse->branch->branch_code }}
                                    @else 
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}(Head Office)
                                    @endif
                                </b>
                            </td>
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ bcadd($variant_price_inc_tax, 0, 2) }}
                            </td>
                            <td class="text-start">
                                {{ $product_warehouse_variant->variant_quantity . ' (' .$product->unit->code_name . ')' }}
                            </td>
                            @php
                                $stockValue = $product_warehouse_variant->variant_quantity * $variant_price_inc_tax;
                            @endphp
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}{{ bcadd($stockValue, 0, 2) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any warehouse.</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif