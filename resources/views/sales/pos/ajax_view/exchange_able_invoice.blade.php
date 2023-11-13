<form id="prepare_to_exchange" action="{{ route('sales.pos.exchange.prepare') }}" method="POST">
    @csrf
    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
    <div class="invoice_info">
        <div class="row">
            <ul class="list-unstyled">
                <li style="font-size: 11px!important;"><b>{{ __("Date") }} : </b>{{ $sale->date . ' ' . $sale->time }}</li>
                <li style="font-size: 11px!important;"><b>{{ __("Invoice ID") }} : </b>{{ $sale->invoice_id }}</li>
                <li style="font-size: 11px!important;"><b>{{ __("Customer") }} : </b>{{ $sale?->customer?->name }}</li>
            </ul>
        </div>
    </div>
    <hr class="m-1">
    <div class="sold_items_table">
        <p class="fw-bold">{{ __("Sold Product List") }}</p>
        <div class="set-height2">
            <div class="table-responsive">
                <table class="table data__table modal-table table-sm sale-product-table">
                    <thead>
                        <tr>
                            <th style="font-size: 11px!important;">{{ __("S/L") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Name") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Sold Quantity") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Discount") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Price Inc. Tax") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Subtotal") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Exchange Qty") }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sale->saleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size: 11px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size: 11px!important;">
                                    <a href="#" class="product-name text-dark" tabindex="-1">{{ $saleProduct->product->name }} {{ $saleProduct?->variant ? $saleProduct?->variant->variant_name : '' }}</a>
                                    <input value="{{ $saleProduct->product_id }}" type="hidden" name="product_ids[]">
                                    <input type="hidden" name="sale_product_ids[]" id="ex_sale_product_id" value="{{ $saleProduct->id }}">
                                    <input type="hidden" name="variant_ids[]" value="{{ $saleProduct->variant_id  ? $saleProduct->variant_id  : 'noid' }}">
                                    <input type="hidden" name="unit_tax_percents[]" id="ex_unit_tax_percent" value="{{ $saleProduct->tax_percent }}">
                                    <input name="unit_tax_amounts[]" type="hidden" id="ex_unit_tax_amount" value="{{ $saleProduct->tax_amount }}">
                                </td>

                                <td style="font-size: 11px!important;">
                                    <b><span class="span_sold_quantity">{{ $saleProduct->quantity .'/'.$saleProduct?->unit?->name}}</span></b>
                                    <input type="hidden" name="sold_quantities[]" class="form-control text-center fw-bold" id="ex_sold_quantity" value="{{ $saleProduct->quantity }}">
                                </td>

                                <td style="font-size: 11px!important;">
                                    <div class="input-group">
                                        <select name="unit_discount_type" id="ex_unit_discount_type" class="form-control">
                                            <option value="1">{{ __("Fixed") }}</option>
                                            <option value="2">{{ __("Percentage") }}</option>
                                        </select>
                                    </div>
                                    <input name="unit_discount[]" type="hidden" id="" value="{{ $saleProduct->unit_discount }}">
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input type="hidden" name="ex_prices_inc_tax[]" id="price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
                                    <span id="span_ex_unit_price_inc_tax" class="fw-bold">{{ $saleProduct->unit_price_inc_tax }}</span>
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input type="hidden" name="sold_subtotals[]" id="ex_subtotal" value="{{ $saleProduct->subtotal }}">
                                    <span id="span_ex_subtotal" class="fw-bold">{{ $saleProduct->subtotal }}</span>
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input type="number" step="any" name="ex_quantities[]" class="form-control text-center fw-bold" id="ex_quantity" value="0.00" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 offset-6">
            <div class="row g-2">
                <label class="col-md-5 text-end"><b>{{ __("Net Total Amount") }}</b></label>
                <div class="col-md-7">
                    <input readonly type="number" step="any" class="form-control fw-bold" name="ex_net_total_amount" id="ex_net_total_amount" value="0.00" tabindex="-1">
                </div>
            </div>
        </div>
    </div>


    <div class="form-group mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> {{ __("Loading") }}...</b></button>
            <button type="submit" class="c-btn button-success float-end">{{ __("Next") }}</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">{{ __("Close") }}</button>
        </div>
    </div>
</form>
