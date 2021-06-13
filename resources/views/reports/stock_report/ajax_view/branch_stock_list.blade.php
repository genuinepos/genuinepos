<table class="display data_tbl data__table">
    <thead>
        <tr class="text-start">
            <th>P.Code(SKU)</th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Current Stock</th>
            <th>Current Stock Value <b><small>(By Unit Cost)</small></b></th>
            <th>Current Stock Value <b><small>(By Unit Price)</small></b></th>
            <th>Potential profit</th>
            <th>Total Unit Sold</th>
            <th>Total Adjusted</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($branchProducts as $branchProduct)
            @if (count($branchProduct->product_branch_variants) > 0)
                @foreach ($branchProduct->product_branch_variants as $product_branch_variant)
                    <tr>
                        <td>{{ $product_branch_variant->product_variant->variant_code }}</td>

                        <td>{{ $branchProduct->product->name . ' - ' . $product_branch_variant->product_variant->variant_name }}
                        </td>

                        <td>{{ $product_branch_variant->product_variant->variant_price }}</td>
                        <td>{{ $product_branch_variant->variant_quantity . '(' . $branchProduct->product->unit->code_name . ')' }}
                        </td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $product_branch_variant->variant_quantity * $product_branch_variant->product_variant->variant_cost_with_tax, 2, '.', '') }}
                        </td>

                        <td>
                            @php
                                $tax = $branchProduct->product->tax ? $branchProduct->product->tax->tax_percent : 0;
                                $sellingPriceIncTax = ($product_branch_variant->product_variant->variant_price / 100) * $tax + $product_branch_variant->product_variant->variant_price;
                            @endphp
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $product_branch_variant->variant_quantity * $sellingPriceIncTax, 2, '.', '') }}
                        </td>

                        <td>
                            @php
                                $frofit = 0;
                                $number_of_sale = 0;
                                $sale_products = DB::table('sale_products')
                                    ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                                    ->where('sales.branch_id', $branchProduct->branch_id)
                                    ->where('sales.status', 1)
                                    ->where('product_variant_id', $product_branch_variant->product_variant_id)
                                    ->get();
                                
                                foreach ($sale_products as $sale_product) {
                                    $frofit += $sale_product->unit_price_inc_tax * $sale_product->quantity - $sale_product->unit_cost_inc_tax * $sale_product->quantity;
                                    $number_of_sale += $sale_product->quantity;
                                }
                            @endphp
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ number_format((float) $frofit, 2, '.', '') }}
                        </td>

                        <td>{{ $number_of_sale . ' (' . $branchProduct->product->unit->code_name . ')' }}
                        </td>
                        <td>
                            @php
                                $total_adjusted = 0;
                                $adjusted_variants = DB::table('stock_adjustment_products')
                                    ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', '=', 'stock_adjustments.id')
                                    ->where('stock_adjustments.branch_id', $branchProduct->branch_id)
                                    ->where('product_variant_id', $product_branch_variant->product_variant_id)
                                    ->get();
                                
                                foreach ($adjusted_variants as $adjusted_variant) {
                                    $total_adjusted += $adjusted_variant->quantity;
                                }
                            @endphp
                            {{ $total_adjusted . ' (' . $branchProduct->product->unit->code_name . ')' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $branchProduct->product->product_code }}</td>
                    <td>{{ $branchProduct->product->name }}</td>
                    <td>{{ $branchProduct->product->product_price }}</td>
                    <td>{{ $branchProduct->product_quantity . '(' . $branchProduct->product->unit->code_name . ')' }}
                    </td>
                    <td>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $branchProduct->product_quantity * $branchProduct->product->product_cost_with_tax, 2, '.', '') }}
                    </td>
                    <td>
                        @php
                            $tax = $branchProduct->product->tax ? $branchProduct->product->tax->tax_percent : 0;
                            $sellingPriceIncTax = ($branchProduct->product->product_price / 100) * $tax + $branchProduct->product->product_price;
                        @endphp
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $branchProduct->product_quantity * $sellingPriceIncTax, 2, '.', '') }}
                    </td>

                    <td>
                        @php
                            $frofit = 0;
                            $number_of_sale = 0;
                            $sale_products = DB::table('sale_products')
                                ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                                ->where('sales.branch_id', $branchProduct->branch_id)
                                ->where('sales.status', 1)
                                ->where('product_id', $branchProduct->product_id)
                                ->get();
                            foreach ($sale_products as $sale_product) {
                                $frofit += $sale_product->unit_price_inc_tax * $sale_product->quantity - $sale_product->unit_cost_inc_tax * $sale_product->quantity;
                                $number_of_sale += $sale_product->quantity;
                            }
                        @endphp
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float) $frofit, 2, '.', '') }}
                    </td>

                    <td>{{ $number_of_sale . ' (' . $branchProduct->product->unit->code_name . ')' }}
                    </td>
                    <td>
                        @php
                            $total_adjusted = 0;
                            $adjusted_variants = DB::table('stock_adjustment_products')
                                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', '=', 'stock_adjustments.id')
                                ->where('stock_adjustments.branch_id', $branchProduct->branch_id)
                                ->where('product_id', $branchProduct->product_id)
                                ->get();
                            
                            foreach ($adjusted_variants as $adjusted_variant) {
                                $total_adjusted += $adjusted_variant->quantity;
                            }
                        @endphp
                        {{ $total_adjusted . ' (' . $branchProduct->product->unit->code_name . ')' }}
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
    });
</script>
