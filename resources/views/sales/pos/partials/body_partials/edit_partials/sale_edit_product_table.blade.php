<style>
    .set-height {
        position: relative;
    }
</style>
<div class="set-height">
    <div class="data_preloader submit_preloader">
        <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table data__table modal-table table-sm sale-product-table">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/L') }}</th>
                    <th scope="col">{{ __('Product') }}</th>
                    <th scope="col">{{ __('Qty/Weight') }}</th>
                    <th scope="col">{{ __('Unit') }}</th>
                    <th scope="col">{{ __('Price Inc. Tax') }}</th>
                    <th scope="col">{{ __('Subtotal') }}</th>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                </tr>
            </thead>

            <tbody id="product_list">
                @php
                    $itemUnitsArray = [];
                @endphp

                @foreach ($sale->saleProducts()->orderBy('product_id', 'asc')->get() as $saleProduct)
                    @php
                        if (isset($saleProduct->product_id)) {
                            $itemUnitsArray[$saleProduct->product_id][] = [
                                'unit_id' => $saleProduct->product->unit->id,
                                'unit_name' => $saleProduct->product->unit->name,
                                'unit_code_name' => $saleProduct->product->unit->code_name,
                                'base_unit_multiplier' => 1,
                                'multiplier_details' => '',
                                'is_base_unit' => 1,
                            ];
                        }
                    @endphp

                    <tr class="product_row">
                        <td class="fw-bold" id="serial">1</td>
                        <td class="text-start">
                            @php
                                $variant = $saleProduct->variant_id ? ' -' . $saleProduct->variant->variant_name : '';
                                $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : 'noid';

                                $productStock = DB::table('product_stocks')
                                    ->where('branch_id', $sale->branch_id)
                                    ->where('warehouse_id', null)
                                    ->where('product_id', $saleProduct->product_id)
                                    ->where('variant_id', $saleProduct->variant_id)
                                    ->first();

                                $currentStock = $productStock ? $productStock->stock : 0;

                                $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;

                                $name = strlen($saleProduct?->product?->name) > 35 ? Str::limit($saleProduct?->product?->name, 35, '...') : $saleProduct?->product?->name;
                                $__name = $name . $variant;
                            @endphp

                            <a href="#" onclick="editProduct(this); return false;" id="edit_product_link" tabindex="-1">{{ $__name }}</a><br/>
                            <span><small id="span_description" style="font-size:9px;">
                                @php
                                    $description = strlen($saleProduct->description) > 40 ? Str::limit($saleProduct->description, 40, '...') : $saleProduct->description;
                                @endphp
                                {{ $description }}
                            </small></span>
                            <input type="hidden" id="is_show_emi_on_pos" value="{{ $saleProduct?->product?->is_show_emi_on_pos }}">
                            <input type="hidden" name="descriptions[]" id="description" value="{{ $description }}">

                            <input type="hidden" id="product_name" value="{{ $__name }}">
                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $saleProduct->product_id }}">
                            <input type="hidden" id="variant_id" name="variant_ids[]" value="{{ $variantId }}">
                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $saleProduct->tax_type }}">
                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $saleProduct->tax_ac_id }}">
                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $saleProduct->unit_tax_percent }}">
                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $saleProduct->unit_tax_amount }}">
                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $saleProduct->unit_discount_type }}">
                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $saleProduct->unit_discount }}">
                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $saleProduct->unit_discount_amount }}">
                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $saleProduct->unit_cost_inc_tax }}">
                            <input type="hidden" name="sale_product_ids[]" value="{{ $saleProduct->id }}">
                            <input type="hidden" id="current_quantity" value="0">
                            <input type="hidden" id="current_stock" value="{{ $currentStock }}">
                            <input type="hidden" class="unique_id" id="{{ $saleProduct->product_id . $variantId }}" value="{{ $saleProduct->product_id . $variantId }}">
                        </td>

                        <td class="text-start">
                            <span id="span_quantity" class="fw-bold">{{ $saleProduct->quantity }}</span>
                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $saleProduct->quantity }}">
                        </td>

                        <td class="text-start">
                            <span id="span_unit">{{ $saleProduct?->unit?->name }}</span>
                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $saleProduct?->unit?->id }}">
                        </td>

                        <td class="text-start">
                            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $saleProduct->unit_price_exc_tax }}">
                            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
                            <span id="span_unit_price_inc_tax" class="fw-bold">{{ $saleProduct->unit_price_inc_tax }}</span>
                        </td>

                        <td class="text-start">
                            <strong><span id="span_subtotal">{{ $saleProduct->subtotal }}</span></strong>
                            <input type="hidden" value="{{ $saleProduct->subtotal }}" readonly name="subtotals[]" id="subtotal">
                        </td>

                        <td class="text-start">
                            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
