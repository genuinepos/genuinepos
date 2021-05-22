@php
use App\Models\ProductOpeningStock;
use App\Models\Product;
@endphp
<style>
    .input-group-text {
        padding: 0px 8px !important;
    }

    .input-group-prepend {
        background: white !important;
    }

</style>

<form id="update_opening_stock_form" action="{{ route('products.opening.stock.update', $productId) }}"
    method="POST">
    @csrf
    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        @foreach ($warehouses as $warehouse)
            <div class="card mt-3">
                <div class="card-header">
                    <p class="m-0"><b>Warehouse : {{ $warehouse->warehouse_name }} - {{ $warehouse->warehouse_code }}</b> </p>
                </div>
                <div class="card_body">
                    <div class="product_stock_table_area">
                        <div class="table-responsive">
                            <table class="display modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-white">Product Name</th>
                                        <th class="text-white">Quantity Remaining</th>
                                        <th class="text-white">Unit Cost Exc.Tax</th>
                                        <th class="text-white">SubTotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $openingStocks = ProductOpeningStock::with(['product', 'product.unit', 'variant'])
                                            ->where('warehouse_id', $warehouse->id)
                                            ->where('product_id', $productId)
                                            ->get();
                                        $totalAmount = 0;
                                    @endphp
                                    @if (count($openingStocks) > 0)
                                        @foreach ($openingStocks as $openingStock)
                                            <tr>
                                                <td class="text">
                                                    {{ $openingStock->product->name . ($openingStock->variant ? ' - ' . $openingStock->variant->variant_name : '') }}
                                                </td>

                                                <td>
                                                    <input type="hidden" name="warehouse_ids[]"
                                                        value="{{ $warehouse->id }}">
                                                    <input type="hidden" name="product_ids[]"
                                                        value="{{ $openingStock->product_id }}">
                                                    <input type="hidden" name="variant_ids[]"
                                                        value="{{ $openingStock->product_variant_id ? $openingStock->product_variant_id : 'noid' }}">

                                                    <div class="input-group width-25 ml-2">
                                                        <input type="number" step="any" name="qunatities[]"
                                                            class="form-control" id="quantity"
                                                            value="{{ $openingStock->quantity }}">
                                                        <div class="input-group-prepend">
                                                            <span
                                                                class="input-group-text input_group_text_custom text-dark">{{ $openingStock->product->unit->code_name }}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                        class="form-control" id="unit_cost_exc_tax"
                                                        value="{{ $openingStock->unit_cost_exc_tax }}">
                                                </td>

                                                <td class="text">
                                                    <b><span
                                                            class="span_subtotal">{{ $openingStock->subtotal }}</span></b>
                                                    <input type="hidden" id="subtotal" name="subtotals[]"
                                                        value="{{ $openingStock->subtotal }}">
                                                </td>
                                            </tr>
                                            @php
                                                $totalAmount += $openingStock->subtotal;
                                            @endphp
                                        @endforeach
                                    @else
                                        @php
                                            $product = Product::with('product_variants', 'unit')
                                                ->where('id', $productId)
                                                ->first();
                                        @endphp
                                        @if ($product->is_variant == 1)
                                            @foreach ($product->product_variants as $variant)
                                                <tr>
                                                    <td class="text">
                                                        {{ $product->name . ' - ' . $variant->variant_name }}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="warehouse_ids[]"
                                                            value="{{ $warehouse->id }}">
                                                        <input type="hidden" name="product_ids[]"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="variant_ids[]"
                                                            value="{{ $variant->id }}">

                                                        <div class="input-group width-25 ml-2">
                                                            <input type="number" step="any" name="qunatities[]"
                                                                class="form-control" id="quantity"
                                                                value="0.00">
                                                            <div class="input-group-prepend">
                                                                <span
                                                                    class="input-group-text input_group_text_custom text-dark">{{ $product->unit->code_name }}</span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                            class="form-control"
                                                            id="unit_cost_exc_tax"
                                                            value="{{ $variant->variant_cost }}">
                                                    </td>

                                                    <td class="text">
                                                        <b><span class="span_subtotal">0.00</span></b>
                                                        <input type="hidden" id="subtotal" name="subtotals[]"
                                                            value="0.00">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text">{{ $product->name }}</td>
                                                <td>
                                                    <input type="hidden" name="warehouse_ids[]"
                                                        value="{{ $warehouse->id }}">
                                                    <input type="hidden" name="product_ids[]"
                                                        value="{{ $product->id }}">
                                                    <input type="hidden" name="variant_ids[]" value="noid">

                                                    <div class="input-group width-25 ml-2">
                                                        <input type="number" step="any" name="qunatities[]"
                                                            class="form-control" id="quantity"
                                                            value="0.00">
                                                        <div class="input-group-prepend">
                                                            <span
                                                                class="input-group-text input_group_text_custom text-dark">{{ $product->unit->code_name }}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                        class="form-control" id="unit_cost_exc_tax"
                                                        value="{{ $product->product_cost }}">
                                                </td>

                                                <td class="text">
                                                    <b><span class="span_subtotal">0.00</span></b>
                                                    <input type="hidden" id="subtotal" name="subtotals[]"
                                                        value="0.00">
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan=""></th>
                                        <th>
                                            Total Amount (Exc.Tax) : <span
                                                class="span_total_amount">{{ number_format($totalAmount, 2) }}</span>
                                            <input type="hidden" id="total_amount" value="{{ $totalAmount }}">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card mt-3">
            <div class="card-header">
                <p class="m-0"><b>Branch : {{ auth()->user()->branch->name }} - {{ auth()->user()->branch->branch_code }}</b> </p>
            </div>
            <div class="card_body">
                <div class="product_stock_table_area">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-white">Product Name</th>
                                    <th class="text-white">Quantity Remaining</th>
                                    <th class="text-white">Unit Cost Exc.Tax</th>
                                    <th class="text-white">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $openingStocks = ProductOpeningStock::with(['product', 'product.unit', 'variant'])
                                        ->where('branch_id', auth()->user()->branch->id)
                                        ->where('product_id', $productId)
                                        ->get();
                                    $totalAmount = 0;
                                @endphp
                                @if (count($openingStocks) > 0)
                                    @foreach ($openingStocks as $openingStock)
                                        <tr>
                                            <td class="text">
                                                {{ $openingStock->product->name . ($openingStock->variant ? ' - ' . $openingStock->variant->variant_name : '') }}
                                            </td>

                                            <td>
                                                <input type="hidden" name="branch_ids[]"
                                                    value="{{ auth()->user()->branch->id }}">
                                                <input type="hidden" name="product_ids[]"
                                                    value="{{ $openingStock->product_id }}">
                                                <input type="hidden" name="variant_ids[]"
                                                    value="{{ $openingStock->product_variant_id ? $openingStock->product_variant_id : 'noid' }}">

                                                <div class="input-group width-25 ml-2">
                                                    <input type="number" step="any" name="qunatities[]"
                                                        class="form-control" id="quantity"
                                                        value="{{ $openingStock->quantity }}">
                                                    <div class="input-group-prepend">
                                                        <span
                                                            class="input-group-text input_group_text_custom text-dark">{{ $openingStock->product->unit->code_name }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                    class="form-control" id="unit_cost_exc_tax"
                                                    value="{{ $openingStock->unit_cost_exc_tax }}">
                                            </td>

                                            <td class="text">
                                                <b><span
                                                        class="span_subtotal">{{ $openingStock->subtotal }}</span></b>
                                                <input type="hidden" id="subtotal" name="subtotals[]"
                                                    value="{{ $openingStock->subtotal }}">
                                            </td>
                                        </tr>
                                        @php
                                            $totalAmount += $openingStock->subtotal;
                                        @endphp
                                    @endforeach
                                @else
                                    @php
                                        $product = Product::with('product_variants', 'unit')
                                            ->where('id', $productId)
                                            ->first();
                                    @endphp
                                    @if ($product->is_variant == 1)
                                        @foreach ($product->product_variants as $variant)
                                            <tr>
                                                <td class="text">
                                                    {{ $product->name . ' - ' . $variant->variant_name }}</td>
                                                <td>
                                                    <input type="hidden" name="branch_ids[]"
                                                        value="{{ auth()->user()->branch->id }}">
                                                    <input type="hidden" name="product_ids[]"
                                                        value="{{ $product->id }}">
                                                    <input type="hidden" name="variant_ids[]"
                                                        value="{{ $variant->id }}">

                                                    <div class="input-group width-25 ml-2">
                                                        <input type="number" step="any" name="qunatities[]"
                                                            class="form-control" id="quantity"
                                                            value="0.00">
                                                        <div class="input-group-prepend">
                                                            <span
                                                                class="input-group-text input_group_text_custom text-dark">{{ $product->unit->code_name }}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                        class="form-control" id="unit_cost_exc_tax"
                                                        value="{{ $product->variant_cost }}">
                                                </td>

                                                <td class="text">
                                                    <b><span class="span_subtotal">0.00</span></b>
                                                    <input type="hidden" id="subtotal" name="subtotals[]"
                                                        value="0.00">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text">{{ $product->name }}</td>
                                            <td>
                                                <input type="hidden" name="branch_ids[]"
                                                    value="{{ auth()->user()->branch->id }}">
                                                <input type="hidden" name="product_ids[]"
                                                    value="{{ $product->id }}">
                                                <input type="hidden" name="variant_ids[]" value="noid">

                                                <div class="input-group width-25 ml-2">
                                                    <input type="number" step="any" name="qunatities[]"
                                                        class="form-control" id="quantity"
                                                        value="0.00">
                                                    <div class="input-group-prepend">
                                                        <span
                                                            class="input-group-text input_group_text_custom text-dark">{{ $product->unit->code_name }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <input type="number" step="any" name="unit_costs_exc_tax[]"
                                                    class="form-control" id="unit_cost_exc_tax"
                                                    value="{{ $product->product_cost }}">
                                            </td>

                                            <td class="text">
                                                <b><span class="span_subtotal">0.00</span></b>
                                                <input type="hidden" id="subtotal" name="subtotals[]" value="0.00">
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan=""></th>
                                    <th>
                                        Total Amount (Exc.Tax) : <span
                                            class="span_total_amount">{{ number_format($totalAmount, 2) }}</span>
                                        <input type="hidden" id="total_amount" value="{{ $totalAmount }}">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal-footer">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
        
    </div>
</form>
@if (count($warehouses) == 0)
    <div class="text-center">
        <h6>There is no Warehuse exists. please add Warehouse first.</h6>
    </div>
@endif
