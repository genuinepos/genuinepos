@if ($product->is_variant == 0)
    <table id="single_product_branch_stock_table" class="table modal-table table-sm">
        <thead>
            <tr class="bg-primary">
                <th class="text-white text-start">Product Code(SKU)</th>
                <th class="text-white text-start">Product</th>
                <th class="text-white text-start">Branch</th>
                <th class="text-white text-start">Unit Price (Inc.Tax)</th>
                <th class="text-white text-start">Current Stock</th>
                <th class="text-white text-start">Stock Value</th>
            </tr>
        </thead>
        <tbody>
            @if ($product->mb_stock > 0)
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
                <tr>
                    <td class="text-start">
                        {{ $product->product_code }}
                    </td>
                    <td class="text-start">
                        {{ $product->name }}
                    </td>

                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                    </td>

                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ bcadd($product_price_inc_tax, 0, 2) }}
                    </td>

                    <td class="text-start">
                        {{ $product->mb_stock . ' (' . $product->unit->code_name . ')' }}
                    </td>
                    @php
                        $stockValue = $product->mb_stock * $product_price_inc_tax;
                    @endphp
                    <td class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ bcadd($stockValue, 0, 2) }}
                    </td>
                </tr>
            @endif

            @if (count($product->product_branches) > 0)
                @foreach ($product->product_branches as $product_branch)
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
                    <tr>
                        <td class="text-start">{{ $product->product_code }}</td>
                        <td class="text-start">{{ $product->name }}</td>
                        <td class="text-start">
                            <b>{{ $product_branch->branch->name . ' - ' .$product_branch->branch->branch_code }}</b>
                        </td>
                        <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($product_price_inc_tax, 0, 2) }}</td>
                        <td class="text-start">{{ $product_branch->product_quantity . ' (' . $product->unit->code_name . ')' }}
                        </td>
                        @php
                            $stockValue = $product_branch->product_quantity * $product_price_inc_tax;
                        @endphp
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($stockValue, 0, 2) }}
                        </td>
                    </tr>
                @endforeach
            @elseif(count($product->product_branches) == 0 && $product->mb_stock == 0)
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any branch.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
@else
    <table id="variant_product_branch_stock_table" class="table modal-table table-sm">
        <thead>
            <tr class="bg-primary">
                <th class="text-white text-start">Product Code(SKU)</th>
                <th class="text-white text-start">Product</th>
                <th class="text-white text-start">Branch</th>
                <th class="text-white text-start">Unit Price (Inc.Tax)</th>
                <th class="text-white text-start">Current Stock</th>
                <th class="text-white text-start">Stock Value</th>
                <th class="text-white text-start">Total Unit Adjusted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->product_variants as $variant)
                @if ($variant->mb_stock > 0)
                @php
                    $tax = $product->tax ? $product->tax->tax_percent : 0;
                    $variant_price_inc_tax = ($variant->variant_price / 100) * $tax + $variant->variant_price;
        
                    if ($product->tax_type == 2) {
                        $inclusiveTax = 100 + $tax;
                        $calc = ($variant->variant_price / $inclusiveTax) * 100;
                        $__tax_amount = $variant->variant_price - $calc;
                        $variant_price_inc_tax = $variant->variant_price + $__tax_amount;
                    }
                @endphp
                    <tr>
                        <td class="text-start">
                            {{ $variant->variant_code }}
                        </td>
                        <td class="text-start">
                            {{ $product->name . ' - ' . $variant->variant_name }}
                        </td>

                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                        </td>

                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($variant_price_inc_tax, 0, 2) }}
                        </td>

                        <td class="text-start">
                            {{ $variant->mb_stock . ' (' . $product->unit->code_name . ')' }}
                        </td>
                        @php
                            $stockValue = $variant->mb_stock * $variant_price_inc_tax;
                        @endphp
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ bcadd($stockValue, 0, 2) }}
                        </td>
                    </tr>
                @endif
            @endforeach

            @if (count($product->product_branches) > 0)
                @foreach ($product->product_branches as $product_branch)
                    @foreach ($product_branch->product_branch_variants as $product_branch_variant)
                        @php
                            $tax = $product->tax ? $product->tax->tax_percent : 0;
                            $variant_price_inc_tax = ($product_branch_variant->product_variant->variant_price / 100) * $tax + $product_branch_variant->product_variant->variant_price;
                            $totalAdjustedQty = 0;
                            $adjustmentProducts = DB::table('stock_adjustment_products')
                                ->join('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                                ->select('stock_adjustment_products.*', 'stock_adjustments.branch_id')
                                ->where('product_id', $product_branch_variant->product_id)
                                ->where('product_variant_id', $product_branch_variant->product_variant_id)
                                ->get();
                            
                            if (count($adjustmentProducts) > 0) {
                                foreach ($adjustmentProducts as $adjustmentProduct) {
                                    if ($adjustmentProduct->branch_id == $product_branch->branch_id) {
                                        $totalAdjustedQty += $adjustmentProduct->quantity;
                                    }
                                }
                            }

                            if ($product->tax_type == 2) {
                                $inclusiveTax = 100 + $tax;
                                $calc = ($product_branch_variant->product_variant->variant_price / $inclusiveTax) * 100;
                                $__tax_amount = $product_branch_variant->product_variant->variant_price - $calc;
                                $variant_price_inc_tax = $product_branch_variant->product_variant->variant_price + $__tax_amount;
                            }
                        @endphp

                        <tr>
                            <td class="text-start">
                                {{ $product_branch_variant->product_variant->variant_code }}
                            </td>
                            <td class="text-start">
                                {{ $product->name . ' - ' . $product_branch_variant->product_variant->variant_name }}
                            </td>

                            <td class="text-start">
                                {{ $product_branch->branch->name . ' - ' . $product_branch->branch->branch_code }}
                            </td>

                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ bcadd($variant_price_inc_tax, 0, 2) }}
                            </td>

                            <td class="text-start">
                                {{ $product_branch_variant->variant_quantity . ' (' . $product->unit->code_name . ')' }}
                            </td>
                            @php
                                $stockValue = $product_branch_variant->variant_quantity * $variant_price_inc_tax;
                            @endphp
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ bcadd($stockValue, 0, 2) }}</td>
                            <td class="text-start">
                                {{ bcadd($totalAdjustedQty, 0, 2) . ' (' . $product->unit->code_name . ')' }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">This product is not available in any branch.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
@endif