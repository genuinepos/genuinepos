
<table id="single_product_warehouse_stock_table" class="table modal-table table-sm">
    <thead>
        <tr class="bg-primary">
            <th class="text-white text-start">Product Code(SKU)</th>
            <th class="text-white text-start">Product</th>
            <th class="text-white text-start">Warehouse</th>
            <th class="text-white text-start">Current Stock</th>
            <th class="text-white text-start">Stock Value({{ json_decode($generalSettings->business, true)['currency'] }})</th>
            <th class="text-white text-start">Total Purchased(+)</th>
            <th class="text-white text-start">Total received(+)</th>
            <th class="text-white text-start">Total Adjusted(-)</th>
            <th class="text-white text-start">Total Transferred(-)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($own_warehouse_stocks as $row)
            @if ($row->variant_name)
                <tr>
                    <td class="text-start">{{ $row->variant_code }}</td>
                    <td class="text-start">{{ $product->name.'('.$row->variant_name.')' }}</td>
                    <td class="text-start">{{ $row->warehouse_name.'/'.$row->warehouse_code }}</td>
                    <td class="text-start"><b>{{ $row->variant_quantity.'/'.$product->unit->code_name }}</b></td>
                    <td class="text-start">
                        @php
                            $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td class="text-start">{{ $row->v_total_purchased.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->v_total_received.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->v_total_adjusted.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->v_total_transferred.'('.$product->unit->code_name.')' }}</td>
                </tr>
            @else 
                <tr>
                    <td class="text-start">{{ $product->product_code }}</td>
                    <td class="text-start">{{ $product->name }}</td>
                    <td class="text-start">{{ $row->warehouse_name.'/'.$row->warehouse_code }}</td>
                    <td class="text-start"><b>{{ $row->product_quantity.'/'.$product->unit->code_name }}</b></td>
                    <td class="text-start">
                        @php
                            $currentStockValue = $product->product_cost_with_tax * $row->product_quantity;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                    </td>
                    <td class="text-start">{{ $row->total_purchased.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->total_received.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->total_adjusted.'('.$product->unit->code_name.')' }}</td>
                    <td class="text-start">{{ $row->total_transferred.'('.$product->unit->code_name.')' }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
