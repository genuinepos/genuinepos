<div class="modal-dialog modal-full-display">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title product_name" id="exampleModalLabel">
                {{ $product->name . ' - ' . $product->product_code }}
            </h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="carousel-item active product_image">
                        <img  class="rounded" style="height:170px;width:190px;"
                            src="{{ asset('public/uploads/product/thumbnail/' . $product->thumbnail_photo) }}" class="d-block w-100">
                    </div>
                </div>
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Code(SKU) : </strong> {{ $product->product_code }}</li>
                        <li><strong>Brand : </strong> {{ $product->brand ? $product->brand->name : 'N/A' }}</li>
                        <li><strong>Unit : </strong> {{ $product->unit->name }}</li>
                        <li><strong>Barcode Type : </strong> {{ $product->barcode_type }}</li>
                        <li><strong>Available Branch: </strong>
                            @if (count($product->product_branches))
                                @foreach ($product->product_branches as $product_branch)
                                     {{ $product_branch->branch->name . '/' . $product_branch->branch->branch_code }},
                                @endforeach
                            @else
                                Yet-to-be-available-in-any-Branch.
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Category : </strong> {{ $product->category->name }}</li>
                        <li><strong>Sub-Category : </strong> {{ $product->child_category ? $product->child_category->name : 'N/A' }}</li>
                        <li><strong>Is For Sale : </strong>{{ $product->is_for_sale == 1 ? 'Yes' : 'No' }}</li>
                        <li><strong>Alert Quantity : </strong>{{ $product->alert_quantity }}</li>
                        <li><strong>Warranty : </strong>
                            {{ $product->warranty ? $product->warranty->name : 'N/A' }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>Expire Date : </strong> {{ $product->expire_date }}</li>
                        <li><strong>Tax : </strong>{{ $product->tax ? $product->tax->tax_name : 'N/A' }}</li>
                        <li><strong>Product Condition : </strong> >{{ $product->product_condition }}
                        </li>
                        <li>
                            <strong>Product Type : </strong>
                            @php  $product_type = ''; @endphp
                            @if ($product->type == 1 && $product->is_variant == 1)
                                @php $product_type = 'Variant'; @endphp
                            @elseif ($product->type == 1 && $product->is_variant == 0)
                                @php $product_type = 'Single'; @endphp
                            @elseif ($product->type == 2) {
                                @php  $product_type = 'Combo'; @endphp
                            @elseif ($product->type == 3) {
                                @php  $product_type = 'Digital'; @endphp
                            @endif
                            {{ $product_type }}
                        </li>
                    </ul>
                </div>
            </div><br>
            @php $tax = $product->tax ? $product->tax->tax_percent : 0  @endphp
            @if ($product->is_variant == 0)
                <div class="row">
                    <div class="table-responsive">
                        <!--single_product_pricing_table-->
                        @include('product.products.ajax_view.partials.single_product_pricing_table')
                        <!--single_product_pricing_table End-->
                    </div>
                </div>
            @elseif($product->is_variant == 1)
                <div class="row">
                    <div class="table-responsive">
                        <!--variant_product_pricing_table-->
                        @include('product.products.ajax_view.partials.variant_product_pricing_table')
                        <!--variant_product_pricing_table End-->
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="heading">
                    <label class="p-0 m-0"><strong>Warehouse Stock Details</strong></label>
                </div>
                <div class="table-responsive" id="warehouse_stock_details">
                    @if ($product->is_variant == 0)
                        <table id="single_product_warehouse_stock_table" class="table modal-table table-sm">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-white text-start">Product Code(SKU)</th>
                                    <th class="text-white text-start">Product</th>
                                    <th class="text-white text-start">Warehouse</th>
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
                                            @endphp
                                            <td class="text-start">{{ $product->product_code }}</td>
                                            <td class="text-start">{{ $product->name }}</td>
                                            <td class="text-start">{{ $product_warehouse->warehouse->warehouse_name . ' - ' . $product_warehouse->warehouse->warehouse_code }}
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
                                            @endphp
                                            <tr>
                                                <td class="text-start">{{ $product_warehouse_variant->product_variant->variant_code }}
                                                </td>
                                                <td class="text-start">{{ $product->name . ' - ' . $product_warehouse_variant->product_variant->variant_name }}
                                                </td>
                                                <td class="text-start">
                                                    {{ $product_warehouse->warehouse->warehouse_name . ' - ' . $product_warehouse->warehouse->warehouse_code }}
                                                </td>
                                                <td class="text-start">
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                    {{ bcadd($variant_price_inc_tax, 0, 2) }}
                                                </td>
                                                <td class="text-start">{{ $product_warehouse_variant->variant_quantity . ' (' . $product->unit->code_name . ')' }}
                                                </td>
                                                @php
                                                    $stockValue = $product_warehouse_variant->variant_quantity * $variant_price_inc_tax;
                                                @endphp
                                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                                    {{ bcadd($stockValue, 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">This product is not available in any
                                            warehouse.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="heading">
                    <label class="p-0 m-0"><strong>Branch Stock Details</strong></label>
                </div>
                <div class="table-responsive" id="branch_stock_details">
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
                                    <th class="text-white text-start">Total Unit Adjusted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($product->product_branches->count() > 0)
                                    @foreach ($product->product_branches as $product_branch)
                                        @php
                                            $totalAdjustedQty = 0;
                                            $adjustmentProducts = DB::table('stock_adjustment_products')
                                                ->join('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                                                ->select('stock_adjustment_products.*', 'stock_adjustments.branch_id')
                                                ->where('product_id', $product_branch->product_id)
                                                ->get();
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
                                                $product_price_inc_tax = ($product->product_price / 100) * $tax + $product->product_price;
                                            @endphp
                                            <td class="text-white text-start">{{ $product->product_code }}</td>
                                            <td class="text-white text-start">{{ $product->name }}</td>
                                            <td class="text-white text-start">{{ $product_branch->branch->name . ' - ' . $product_branch->branch->branch_code }}
                                            </td>
                                            <td class="text-white text-start"> {{ json_decode($generalSettings->business, true)['currency'] }}
                                                {{ bcadd($product_price_inc_tax, 0, 2) }}</td>
                                            <td class="text-white text-start">{{ $product_branch->product_quantity . ' (' . $product->unit->code_name . ')' }}
                                            </td>
                                            @php
                                                $stockValue = $product_branch->product_quantity * $product_price_inc_tax;
                                            @endphp
                                            <td class="text-white text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                                {{ bcadd($stockValue, 0, 2) }}</td>
                                            <td class="text-white text-start">{{ bcadd($totalAdjustedQty, 0, 2) . ' (' . $product->unit->code_name . ')' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
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
                </div>
            </div>
        </div>
        <div class="modal-footer text-end">
            {{-- <button type="button" class="btn btn-sm btn-primary print_btn">Print</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button> --}}
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="c-btn btn_blue print_btn">Print</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                    </div>
                </div>
        </div>
    </div>
</div>
