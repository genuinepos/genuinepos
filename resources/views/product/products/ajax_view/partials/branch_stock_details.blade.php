<table id="single_product_branch_stock_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">Product Code(SKU)</th>
            <th class="text-white text-start">Product</th>
            <th class="text-white text-start">Business Location</th>
            <th class="text-white text-start">Current Stock</th>
            <th class="text-white text-start">Stock Value</th>
            <th class="text-white text-start">Total Sale</th>
        </tr>
    </thead>
    <tbody>
        <!-- Main Branch single product Stock -->
        @if (!auth()->user()->branch_id)
            @if (count($product->product_variants) > 0)
                @foreach ($product->product_variants as $product_variant)
                    <tr>
                        <td class="text-start">{{ $product_variant->variant_code }}</td>
                        <td class="text-start">{{ $product->name.' - '.$product_variant->variant_name }}</td>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                        </td>

                        <td class="text-start"><b>{{ $product_variant->mb_stock . ' (' . $product->unit->code_name . ')' }}</b></td>
                        @php
                            $stockValue = $product_variant->mb_stock * $product->product_cost_with_tax;
                        @endphp
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                            {{ App\Utils\Converter::format_in_bdt($stockValue) }}
                        </td>
                        <td>{{ $product_variant->mb_total_sale }}</td>
                    </tr>
                @endforeach
            @else 
                <tr>
                    <td class="text-start">{{ $product->product_code }}</td>
                    <td class="text-start">{{ $product->name }}</td>
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                    </td>

                    <td class="text-start">{{ $product->mb_stock . ' (' . $product->unit->code_name . ')' }}</td>
                    @php
                        $stockValue = $product->mb_stock * $product->product_cost_with_tax;
                    @endphp
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                        {{ App\Utils\Converter::format_in_bdt($stockValue) }}
                    </td>
                    <td>{{ $product->mb_total_sale }}</td>
                </tr>    
            @endif
        @else 
            @if (count($won_branch_stocks) > 0)
                @foreach ($won_branch_stocks as $row)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $product->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">{!! $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' !!}</td>
                            <td class="text-start">{{ $row->variant_quantity.'('.$product->unit->code_name.')' }}</td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->v_total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @else 
                        <tr>
                            <td class="text-start">{{ $product->product_code }}</td>
                            <td class="text-start">{{ $product->name }}</td>
                            <td class="text-start">{!! $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' !!}</td>
                            <td class="text-start">{{ $row->product_quantity.'('.$product->unit->code_name.')' }}</td>
                            <td class="text-start">
                                @php
                                    $currentStockValue = $product->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-start">{{ $row->total_sale.'('.$product->unit->code_name.')' }}</td>
                        </tr>
                    @endif
                @endforeach
            @else 
                <tr><th colspan="6" class="text-center">This Product Is Not Available In This Business Location</th></tr>
            @endif
        @endif
    </tbody>
</table>
