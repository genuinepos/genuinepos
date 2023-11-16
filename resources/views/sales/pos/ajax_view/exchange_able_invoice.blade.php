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
                            <th style="font-size: 11px!important;">{{ __("Vat/Tax") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Price Inc. Tax") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Subtotal") }}</th>
                            <th style="font-size: 11px!important;">{{ __("Exchange Qty") }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($saleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size: 10px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size: 10px!important;">
                                    <a href="#" class="product-name text-dark" tabindex="-1">{{ $saleProduct->product_name }} {{ $saleProduct?->variant_name ? ' - '.$saleProduct?->variant_name : '' }}</a>
                                    <input value="{{ $saleProduct->product_id }}" type="hidden" name="product_ids[]">
                                    {{-- <input type="hidden" name="sale_product_ids[]" id="ex_sale_product_id" value="{{ $saleProduct->id }}"> --}}
                                    <input type="hidden" name="variant_ids[]" value="{{ $saleProduct->variant_id  ? $saleProduct->variant_id  : 'noid' }}">
                                </td>

                                <td style="font-size: 10px!important;">
                                    <b><span class="span_sold_quantity">{{ $saleProduct->quantity . '/' . $saleProduct?->unit_name}}</span></b>
                                    <input type="hidden" name="sold_quantities[]" class="form-control text-center fw-bold" id="ex_sold_quantity" value="{{ $saleProduct->quantity }}">
                                </td>

                                <td style="font-size: 10px!important;">
                                    <div class="input-group">
                                        <select onchange="changeExchangeDiscountType(this); return false;" name="unit_discount_types[]" id="ex_unit_discount_type" class="form-control">
                                            <option value="1">{{ __("Fixed") }}</option>
                                            <option {{ $saleProduct->unit_discount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __("Percentage") }}</option>
                                        </select>

                                        <input oninput="changeExchangeDiscount(this); return false;" type="number" name="unit_discounts[]" step="any" class="form-control fw-bold" id="ex_unit_discount" value="{{ $saleProduct->unit_discount }}" autocomplete="off">
                                        <input type="hidden" name="unit_discount_amounts[]" class="form-control" id="ex_unit_discount_amount" value="{{ $saleProduct->unit_discount_amount }}">
                                    </div>
                                </td>

                                <td style="font-size: 10px!important;">
                                    <span id="span_ex_unit_tax_percent" class="fw-bold">{{ $saleProduct->unit_tax_percent }}%</span>
                                    <input type="hidden" name="tax_types[]" id="ex_tax_type" value="{{ $saleProduct->tax_type }}">

                                    <input type="hidden" name="unit_tax_percents[]" id="ex_unit_tax_percent" value="{{ $saleProduct->unit_tax_percent }}">
                                    <input name="unit_tax_amounts[]" type="hidden" id="ex_unit_tax_amount" value="{{ $saleProduct->unit_tax_amount }}">
                                </td>

                                <td style="font-size: 10px!important;">
                                    <input type="hidden" name="unit_prices_exc_tax[]" id="ex_price_exc_tax" value="{{ $saleProduct->unit_price_exc_tax }}">
                                    <input type="hidden" name="unit_prices_inc_tax[]" id="ex_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
                                    <span id="span_ex_price_inc_tax" class="fw-bold">{{ $saleProduct->unit_price_inc_tax }}</span>
                                </td>

                                <td style="font-size: 10px!important;">
                                    <input type="hidden" name="subtotals[]" id="ex_subtotal" value="{{ $saleProduct->subtotal }}">
                                    <span id="span_ex_subtotal" class="fw-bold">{{ $saleProduct->subtotal }}</span>
                                </td>

                                <td style="font-size: 10px!important;">
                                    <input oninput="inputExchangeQty(this); return false;" type="number" step="any" name="ex_quantities[]" class="form-control text-center fw-bold" id="ex_quantity" value="0.00" autocomplete="off">
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
            <div class="row gx-2">
                <label class="col-md-5 text-end"><b>{{ __("Sale Discount") }}</b></label>
                <div class="col-md-7">
                    <div class="input-group">
                        @php
                            $saleDiscount = '';
                            if ($sale->order_discount_type == 1) {

                                $saleDiscount = '('.__('Fixed') . ')=' . $sale->order_discount_amount;
                            }else {

                                $saleDiscount = '('.$sale->order_discount . '%)=' . $sale->order_discount_amount;
                            }
                        @endphp

                        <input readonly type="text" step="any" class="form-control fw-bold" value="{{ $saleDiscount }}">
                    </div>
                </div>
            </div>

            <div class="row gx-2 mt-1">
                <label class="col-md-5 text-end"><b>{{ __("Adjusted Exchange Discount") }}</b></label>
                <div class="col-md-7">
                    <input type="number" step="any" class="form-control fw-bold" id="adjusted_discount" value="0.00">
                </div>
            </div>

            <div class="row gx-2 mt-1">
                <label class="col-md-5 text-end"><b>{{ __("Exchange Net Total Amount") }}</b></label>
                <div class="col-md-7">
                    <input readonly type="number" step="any" name="net_total_amount" class="form-control fw-bold" id="ex_net_total_amount" value="0.00">
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

<script>
    function calculateExchangeEditOrAddAmount(tr) {

        var ex_tax_percent = tr.find('#ex_unit_tax_percent').val() ? tr.find('#ex_unit_tax_percent').val() : 0.00;
        var ex_tax_type = tr.find('#ex_tax_type').val();
        var ex_unit_discount_type = tr.find('#ex_unit_discount_type').val();
        var ex_sold_quantity = tr.find('#ex_sold_quantity').val() ? tr.find('#ex_sold_quantity').val() : 0;
        var ex_quantity = tr.find('#ex_quantity').val() ? tr.find('#ex_quantity').val() : 0;
        var ex_price_exc_tax = tr.find('#ex_price_exc_tax').val() ? tr.find('#ex_price_exc_tax').val() : 0;
        var ex_unit_discount = tr.find('#ex_unit_discount').val() ? tr.find('#ex_unit_discount').val() : 0;

        var discountAmount = 0;
        if (ex_unit_discount_type == 1) {

            discountAmount = ex_unit_discount;
        } else {

            discountAmount = (parseFloat(ex_price_exc_tax) / 100) * parseFloat(ex_unit_discount);
        }

        var unitPriceWithDiscount = parseFloat(ex_price_exc_tax) - parseFloat(discountAmount);
        var taxAmount = parseFloat(unitPriceWithDiscount) / 100 * parseFloat(ex_tax_percent);
        var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);

        if (ex_tax_type == 2) {

            var inclusiveTax = 100 + parseFloat(ex_tax_percent);
            var calcTax = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
            taxAmount =  parseFloat(unitPriceWithDiscount) - parseFloat(calcTax);
            unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);
        }

        tr.find('#ex_unit_tax_amount').val(parseFloat(taxAmount).toFixed(2));
        tr.find('#ex_unit_discount_amount').val(parseFloat(parseFloat(discountAmount)).toFixed(2));
        tr.find('#ex_price_inc_tax').val(parseFloat(parseFloat(unitPriceIncTax)).toFixed(2));
        tr.find('#span_ex_price_inc_tax').html(parseFloat(parseFloat(unitPriceIncTax)).toFixed(2));

        var __ex_quantity = ex_quantity <= 0 ? parseFloat(ex_quantity) - 1 : parseFloat(ex_quantity);
        var __quantity = parseFloat(ex_sold_quantity) + parseFloat(__ex_quantity);
        var __fQuantity = __quantity == 0 ? 1 : __quantity;
        var subtotal = parseFloat(unitPriceIncTax) * parseFloat(__fQuantity);
        tr.find('#ex_subtotal').val(parseFloat(subtotal).toFixed(2));
        tr.find('#span_ex_subtotal').html(parseFloat(subtotal).toFixed(2));

        exchangeCalculateTotalAmount();
    }

    function changeExchangeDiscountType(e) {

        var tr = $(e).closest('tr');
        calculateExchangeEditOrAddAmount(tr);
    }

    function changeExchangeDiscount(e) {

        var tr = $(e).closest('tr');
        calculateExchangeEditOrAddAmount(tr);
    }

    function inputExchangeQty(e) {

        var tr = $(e).closest('tr');

        var exQty = $(e).val();
        var soldQty = tr.find('#ex_sold_quantity').val();

        if (parseFloat(exQty) < 0) {

            var sum = parseFloat(soldQty) + parseFloat(exQty);

            if (sum < 0) {

                toastr.error("{{__('Exchange quantity subtraction value must not be greater then sold quantity.')}}");
                $(e).val(- parseFloat(soldQty));
                return;
            }
        }

        calculateExchangeEditOrAddAmount(tr);
    }

    function exchangeCalculateTotalAmount() {

        var quantities = document.querySelectorAll('#ex_quantity');
        var ex_unit_discount_amount = document.querySelectorAll('#ex_unit_discount_amount');
        var subtotals = document.querySelectorAll('#ex_subtotal');

        // Update Net total Amount
        var netTotalAmount = 0;
        var adjustedDiscount = 0;
        var i = 0;
        subtotals.forEach(function (subtotal) {

            var __exQty = quantities[i].value ? quantities[i].value : 0;
            if (parseFloat(__exQty) != 0) {

                netTotalAmount += parseFloat(subtotal.value);
                var discount = ex_unit_discount_amount[i].value ? ex_unit_discount_amount[i].value : 0;
                adjustedDiscount += parseFloat(discount);
            }

            i++
        });

        $('#ex_net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
        $('#adjusted_discount').val(parseFloat(adjustedDiscount).toFixed(2));
    }

    function orderDiscountChange() {

        exchangeCalculateTotalAmount();
    }
</script>
