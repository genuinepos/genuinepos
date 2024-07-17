<div class="row">
    <p class="m-0"><span class="fw-bold">{{ __("Store/Company") }}</span> :
        @if (auth()->user()->branch_id)
            @if (auth()->user()?->branch?->parentBranch)
                {{ auth()->user()?->branch?->parentBranch->name.'('.auth()->user()?->branch?->area_name.')-'.auth()->user()?->branch?->branch_code }}
            @else
                {{ auth()->user()?->branch?->name.'('.auth()->user()?->branch?->area_name.')-'.auth()->user()?->branch?->branch_code }}
            @endif
        @else
            {{ $generalSettings['business_or_shop__business_name'].'('.__('Company').')' }}
        @endif
    </p>

    @if ($product->is_variant == App\Enums\BooleanType::False->value)
        <div class="product_stock_table_area">
            <div class="table-responsive">
                <table id="" class="display table modal-table table-sm">
                    <thead>
                        <tr class="bg-secondary">
                            <th class="text-white">{{ __("Product") }}</th>
                            <th class="text-white">{{ __("Quantity") }}</th>
                            <th class="text-white">{{ __("Unit Cost Inc. Tax") }}</th>
                            <th class="text-white">{{ __("Subtotal") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $openingStockProduct = '';
                            if (auth()->user()->branch_id) {

                                $openingStockProduct = auth()->user()->branch?->openingStockProduct()
                                ->where('product_id', $product->id)
                                ->where('variant_id', null)->first();
                            }else {

                                $openingStockProduct = DB::table('product_opening_stocks')
                                ->where('branch_id', null)
                                ->where('warehouse_id', null)
                                ->where('product_id', $product->id)
                                ->where('variant_id', null)->first();
                            }
                        @endphp
                        <tr>
                            <td>
                                {{ $product->name }}
                                <input type="hidden" name="product_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->product_id : $product->id }}">
                                <input type="hidden" name="variant_ids[]">
                                <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                <input type="hidden" name="warehouse_ids[]">
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
                            <th class="text-white">{{ __("Product") }}</th>
                            <th class="text-white">{{ __("Quantity") }}</th>
                            <th class="text-white">{{ __("Unit Cost Inc. Tax") }}</th>
                            <th class="text-white">{{ __("Subtotal") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants()->orderBy('id', 'desc')->get() as $variant)
                            @php
                                $openingStockProduct = '';
                                if (auth()->user()->branch_id) {
                                    $openingStockProduct = auth()->user()->branch?->openingStockProduct()
                                    ->where('product_id', $product->id)
                                    ->where('variant_id', $variant->id)
                                    ->first();
                                }else {

                                    $openingStockProduct = DB::table('product_opening_stocks')
                                    ->where('branch_id', null)
                                    ->where('warehouse_id', null)
                                    ->where('product_id', $product->id)
                                    ->where('variant_id', $variant->id)->first();
                                }
                            @endphp
                        <tr>
                            <td>
                                {!! $product->name . ' - <span class="fw-bold">' . $variant->variant_name.'</span>' !!}
                                <input type="hidden" name="product_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->product_id : $product->id }}">
                                <input type="hidden" name="variant_ids[]" value="{{ $openingStockProduct ? $openingStockProduct->variant_id : $variant->id }}">
                                <input type="hidden" name="branch_ids[]" value="{{ auth()->user()->branch_id }}">
                                <input type="hidden" name="warehouse_ids[]">
                            </td>

                            <td>
                                <input type="number" step="any" name="quantities[]" class="form-control fw-bold" id="ops_quantity" value="{{ $openingStockProduct ? $openingStockProduct->quantity : 0.00 }}" autocomplete="off">
                            </td>

                            <td>
                                <input required type="number" step="any" name="unit_costs_inc_tax[]" class="form-control fw-bold" id="ops_unit_cost_inc_tax" value="{{ $openingStockProduct ? $openingStockProduct->unit_cost_inc_tax : $variant->variant_cost_with_tax }}">
                            </td>

                            <td>
                                <input readonly type="number" step="any" name="subtotals[]" class="form-control fw-bold" id="ops_subtotal" value="{{ $openingStockProduct ? $openingStockProduct->subtotal : 0.00 }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
