<table id="variant_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">Variations</th>
            <th class="text-white text-start">Variant Code (SKU)</th>
            <th class="text-white text-start">Default Purchase Price (Exc. tax)</th>
            <th class="text-white text-start">Default Purchase Price (Inc. tax)</th>
            <th class="text-white text-start">x Margin(%)</th>
            <th class="text-white text-start">Default Selling Price (Exc. tax)</th>
            <th class="text-white text-start">Default Selling Price (Inc. tax)</th>
            @if (count($price_groups) > 0)
                <th class="text-white text-start">Price Group</th>
            @endif
            <th class="text-white text-start">Variation Images</th>
        </tr>
    </thead>
    <tbody class="variant_product_pricing_table_body">
        @foreach ($product->product_variants as $variant)
            @php
                $priceIncTax = ($variant->variant_price / 100 * $tax) + $variant->variant_price;
                if ($product->tax_type == 2) {
                    $inclusiveTax = 100 + $tax;
                    $calc = ($variant->variant_price / $inclusiveTax) * 100;
                    $__tax_amount = $variant->variant_price - $calc;
                    $priceIncTax = $variant->variant_price + $__tax_amount;
                }
            @endphp
            <tr>
                <td class="text-start">{{ $variant->variant_name }}</td>
                <td class="text-start">{{ $variant->variant_code }}</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ $variant->variant_cost }}</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ $variant->variant_cost_with_tax }}</td>
                <td class="text-start"> {{ $variant->variant_profit }}</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ bcadd($priceIncTax, 0, 2) }}
                </td>
                <td class="text-start">
                    
                </td>
                
                @if (count($price_groups) > 0)
                    <td class="text-start">
                        @foreach ($price_groups as $pg)
                            @php
                                $price_group_product = DB::table('price_group_products')
                                ->where('price_group_id', $pg->id)
                                ->where('product_id', $product->id)
                                ->where('variant_id', $variant->id)
                                ->first();
                                $groupPrice = 0;
                                if ($price_group_product) {
                                    $groupPrice = $price_group_product->price;
                                }
                            @endphp
                            <p class="m-0 p-0"><strong>{{ $pg->name }}</strong> - {{ json_decode($generalSettings->business, true)['currency'].' '.$groupPrice}}</p>
                        @endforeach
                    </td>
                @endif

                <td class="text-start">
                    @if ($variant->variant_image)
                        <img src="{{ asset('public/uploads/product/variant_image/'. $variant->variant_image) }}">
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>