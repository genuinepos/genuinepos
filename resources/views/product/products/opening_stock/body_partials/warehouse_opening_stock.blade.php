@if (count($warehouses) > 0)
    @foreach ($warehouses as $warehouse)
        <div class="row mt-2">
            <div class="col-md-12">
                <p class="m-0 mb-1" style="border-bottom:1px solid black;"><span class="fw-bold">{{ __('Warehouse') }}</span> :
                    {{ $warehouse->warehouse_name . ' - ' . $warehouse->warehouse_code }}
                </p>

                @if ($product->is_variant == App\Enums\BooleanType::False->value)
                    <div class="product_stock_table_area">
                        <div class="table-responsive">
                            <table id="" class="display table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">{{ __('Product') }}</th>
                                        <th class="text-white">{{ __('Quantity') }}</th>
                                        <th class="text-white">{{ __('Unit Cost Inc. Tax') }}</th>
                                        <th class="text-white">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $openingStockProduct = '';
                                        $openingStockProduct = $warehouse
                                            ->openingStockProduct()
                                            ->where('product_id', $product->id)
                                            ->where('variant_id', null)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $product->name }}
                                            <input type="hidden" name="product_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->product_id : $product->id }}">
                                            <input type="hidden" name="variant_ids[]">
                                            <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                            <input type="hidden" name="warehouse_ids[]" value="{{ $warehouse->id }}">
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="quantities[]" class="form-control fw-bold" id="ops_quantity" value="{{ $openingStockProduct ? $openingStockProduct->quantity : 0.00 }}" autocomplete="off">
                                        </td>

                                        <td>
                                            <input required type="number" step="any" name="unit_costs_inc_tax[]" class="form-control fw-bold" id="ops_unit_cost_inc_tax" value="{{ $openingStockProduct ? $openingStockProduct->unit_cost_inc_tax : $product->product_cost_with_tax }}" autocomplete="off">
                                        </td>

                                        <td>
                                            <input readonly type="number" step="any" name="subtotals[]" class="form-control fw-bold" id="ops_subtotal" value="{{ $openingStockProduct ? $openingStockProduct->subtotal : 0.00 }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="product_stock_table_area">
                        <div class="table-responsive">
                            <table id="" class="display table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white">{{ __('Product') }}</th>
                                        <th class="text-white">{{ __('Quantity') }}</th>
                                        <th class="text-white">{{ __('Unit Cost Inc. Tax') }}</th>
                                        <th class="text-white">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants()->orderBy('id', 'desc')->get() as $variant)
                                        @php
                                            $openingStockProduct = $warehouse
                                                ->openingStockProduct()
                                                ->where('product_id', $product->id)
                                                ->where('variant_id', $variant->id)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                {!! $product->name . ' - <span class="fw-bold">' . $variant->variant_name . '</span>' !!}
                                                <input type="hidden" name="product_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->product_id : $product->id }}">
                                                <input type="hidden" name="variant_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->variant_id : $variant->id }}">
                                                <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                                <input type="hidden" name="warehouse_ids[]" value="{{ $warehouse->id }}">
                                            </td>

                                            <td>
                                                <input type="number" step="any" name="quantities[]" class="form-control fw-bold" id="ops_quantity" value="{{ $openingStockProduct ? $openingStockProduct->quantity : 0.0 }}" autocomplete="off">
                                            </td>

                                            <td>
                                                <input required type="number" step="any" name="unit_costs_inc_tax[]" class="form-control fw-bold" id="ops_unit_cost_inc_tax" value="{{ $openingStockProduct ? $openingStockProduct->unit_cost_inc_tax : $variant->variant_cost_with_tax }}" autocomplete="off">
                                            </td>

                                            <td>
                                                <input readonly type="number" step="any" name="subtotals[]" class="form-control fw-bold" id="ops_subtotal" value="{{ $openingStockProduct ? $openingStockProduct->subtotal : 0.0 }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif
