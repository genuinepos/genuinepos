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
                            <th style="font-size: 11px!important;">{{ __("Unit") }}</th>
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
                                    <input value="{{ $saleProduct->product_id }}" type="hidden" class="productId-{{ $saleProduct->product_id }}" name="product_ids[]">
                                    <input type="hidden" name="sale_product_ids[]" id="sale_product_id" value="{{ $saleProduct->id }}">
                                    <input type="hidden" name="variant_ids[]" value="{{ $saleProduct->variant_id  ? $saleProduct->variant_id  : 'noid' }}">
                                    <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $saleProduct->tax_percent }}">
                                    <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="{{ $saleProduct->tax_amount }}">
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input value="{{ $saleProduct->quantity }}" readonly name="sold_quantities[]" type="number" step="any" class="form-control text-center fw-bold" id="sold_quantity">
                                </td>

                                <td style="font-size: 11px!important;">
                                    <b><span class="sold_unit">{{ $saleProduct?->unit?->name }}</span></b>
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input name="sold_prices_inc_tax[]" type="hidden" id="sold_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
                                    <b><span class="sold_unit_price_inc_tax">{{ $saleProduct->unit_price_inc_tax }}</span> </b>
                                </td>

                                <td style="font-size: 11px!important;">
                                    <input value="{{ $saleProduct->subtotal }}" name="sold_subtotals[]" type="hidden" id="sold_subtotal">
                                    <b><span class="sold_subtotal">{{ $saleProduct->subtotal }}</span></b>
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

    <div class="form-group mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
            <button type="submit" class="c-btn button-success float-end">@lang('menu.next')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('menu.close')</button>
        </div>
    </div>
</form>
