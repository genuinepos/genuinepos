<table id="single_product_pricing_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">Prodcut cost (Exc.Tax)</th>
            <th class="text-white text-start">Prodcut cost (Inc.Tax)</th>
            <th class="text-white text-start">Profit Margin(%)</th>
            <th class="text-white text-start">Default Selling Price (Exc.Tax)</th>
            <th class="text-white text-start">Default Selling Price (Inc.Tax)</th>
            @if (count($price_groups) > 0)
                <th class="text-white text-start">Price Group</th>
            @endif
        </tr>
    </thead>
    <tbody class="single_product_pricing_table_body">
        <tr>
            <td class="text-start">
                {{ json_decode($generalSettings->business, true)['currency'] }}
                {{ $product->product_cost }}
            </td>
            <td class="text-start">
                {{ json_decode($generalSettings->business, true)['currency'] }}
                {{ $product->product_cost_with_tax }}
            </td>
            <td class="text-start">{{ $product->profit }}</td>
            <td class="text-start">
                {{ json_decode($generalSettings->business, true)['currency'] }}
                {{ $product->product_price }}
            </td>
            <td class="text-start">
                {{ json_decode($generalSettings->business, true)['currency'] }}
                {{ ($product->product_price / 100) * $tax + $product->product_price }}
            </td>
            @if (count($price_groups) > 0)
                <td class="text-start">
                    @foreach ($price_groups as $pg)
                        @php
                            $price_group_product = DB::table('price_group_products')
                            ->where('price_group_id', $pg->id)->where('product_id', $product->id)->first();
                            $groupPrice = 0;
                            if ($price_group_product) {
                                $groupPrice = $price_group_product->price;
                            }
                        @endphp
                        <p class="p-0 m-0"><strong>{{ $pg->name }}</strong> - {{ json_decode($generalSettings->business, true)['currency'].' '.$groupPrice}}</p>
                    @endforeach
                </td>
            @endif
        </tr>
    </tbody>
</table>