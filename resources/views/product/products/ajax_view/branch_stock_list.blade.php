@if ($product->is_variant == 0)
    <table id="single_product_branch_stock_table" class="table modal-table table-sm custom-table">
        <thead>
            <tr class="bg-primary">
                <th class="text-white" scope="col">Product Code(SKU)</th>
                <th class="text-white" scope="col">Product</th>
                <th class="text-white" scope="col">Branch</th>
                <th class="text-white" scope="col">Unit Price (Inc.Tax)</th>
                <th class="text-white" scope="col">Current Stock</th>
                <th class="text-white" scope="col">Stock Value</th>
                <th class="text-white" scope="col">Total Unit Adjusted</th>
            </tr>
        </thead>
        <tbody>
            @if($product->product_branches->count() > 0)
                @foreach ($product->product_branches as $product_branch)
                    @php
                        $totalAdjustedQty = 0;
                        $adjustmentProducts = DB::table('stock_adjustment_products')->join('stock_adjustments','stock_adjustment_products.stock_adjustment_id','stock_adjustments.id')->select('stock_adjustment_products.*','stock_adjustments.branch_id')->where('product_id', $product_branch->product_id)->get();   
                        if ($adjustmentProducts->count() > 0) {
                            foreach ($adjustmentProducts as $adjustmentProduct) {
                                if ($adjustmentProduct->branch_id == $product_branch->branch_id) {
                                    $totalAdjustedQty += $adjustmentProduct->quantity;
                                }
                            }
                        } 
                    @endphp
                    <tr>
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $product_price_inc_tax = ($product->product_price / 100 * $tax) + $product->product_price;
                        @endphp
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product_branch->branch->name .' - '. $product_branch->branch->branch_code }}</td>
                        <td>{{ number_format($product_price_inc_tax, 2) }}</td>
                        <td>{{ $product_branch->product_quantity .' ('. $product->unit->code_name.')' }}</td>
                        @php
                            $stockValue = $product_branch->product_quantity * $product_price_inc_tax;
                        @endphp
                        <td>{{ number_format($stockValue, 2)  }}</td>
                        <td>{{ number_format($totalAdjustedQty, 2).' ('. $product->unit->code_name.')'  }} </td>
                    </tr>
                @endforeach
            @else 
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any branch.</td>
                </tr>
            @endif
        </tbody>
    </table>
@else 
    <table id="variant_product_branch_stock_table" class="table table-sm custom-table">
        <thead>
            <tr class="bg-primary">
                <th class="text-white" scope="col">Product Code(SKU)</th>
                <th class="text-white" scope="col">Product</th>
                <th class="text-white" scope="col">Branch</th>
                <th class="text-white" scope="col">Unit Price (Inc.Tax)</th>
                <th class="text-white" scope="col">Current Stock</th>
                <th class="text-white" scope="col">Stock Value</th>
                <th class="text-white" scope="col">Total Unit Adjusted</th>
            </tr>
        </thead>
        <tbody>
            @if ($product->product_branches->count() > 0)
                @foreach ($product->product_branches as $product_branch)
                    @foreach ($product_branch->product_branch_variants as $product_branch_variant)
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $variant_price_inc_tax = ($product_branch_variant->product_variant->variant_price / 100 * $tax) + $product_branch_variant->product_variant->variant_price;

                            $totalAdjustedQty = 0;
                            
                            $adjustmentProducts = DB::table('stock_adjustment_products')->join('stock_adjustments','stock_adjustment_products.stock_adjustment_id','stock_adjustments.id')->select('stock_adjustment_products.*','stock_adjustments.branch_id')->where('product_id', $product_branch_variant->product_id)->where('product_variant_id', $product_branch_variant->product_variant_id)->get(); 

                            if ($adjustmentProducts->count() > 0) {
                                foreach ($adjustmentProducts as $adjustmentProduct) {
                                    if ($adjustmentProduct->branch_id == $product_branch->branch_id) {
                                        $totalAdjustedQty += $adjustmentProduct->quantity;
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $product_branch_variant->product_variant->variant_code }}</td>
                            <td>{{ $product->name.' - '.$product_branch_variant->product_variant->variant_name }}</td>
                            <td>{{ $product_branch->branch->name .' - '. $product_branch->branch->branch_code }}</td>
                            <td>{{ number_format($variant_price_inc_tax, 2) }}</td>
                            <td>{{ $product_branch_variant->variant_quantity.' ('.$product->unit->code_name.')' }}</td>
                            @php
                                $stockValue = $product_branch_variant->variant_quantity * $variant_price_inc_tax;
                            @endphp
                            <td>{{ number_format($stockValue, 2)  }}</td>
                            <td>{{ number_format($totalAdjustedQty, 2).' ('. $product->unit->code_name.')'  }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else 
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any branch.</td>
                </tr>
            @endif
        </tbody>
    </table>
@endif