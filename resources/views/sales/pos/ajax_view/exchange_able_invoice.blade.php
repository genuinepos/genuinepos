<form id="prepare_to_exchange" action="{{ route('sales.pos.prepare.exchange') }}" method="POST">
    @csrf
    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
    <div class="invoice_info">
        <div class="row">
            <ul class="list-unstyled">
                <li><b>@lang('menu.date') :</b> {{ $sale->date.' '.$sale->time }}</li>
                <li><b>@lang('menu.invoice_no') :</b> {{ $sale->invoice_id }}</li>
                <li><b>@lang('menu.customer') :</b> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</li>
            </ul>
        </div>
    </div>
    <hr class="m-1">
    <div class="sold_items_table">
        <p><b>Item List</b></p>
        <div class="set-height2">
            <div class="table-responsive">
                <table class="table data__table modal-table table-sm sale-product-table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('menu.sl')</th>
                            <th scope="col">@lang('menu.name')</th>
                            <th scope="col">@lang('menu.sold_quantity')</th>
                            <th scope="col">@lang('menu.unit')</th>
                            <th scope="col">@lang('menu.price_inc_tax')</th>
                            <th scope="col">@lang('menu.sub_total')</th>
                            <th scope="col">@lang('menu.ex_qty')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sale->sale_products as $item)
                            <tr>
                                <td class="serial text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    <a href="#" class="product-name text-dark" tabindex="-1">{{ $item->product->name }} {{$item->variant ? $item->variant->variant_name : ''}}</a>
                                    <input value="{{ $item->product_id }}" type="hidden" class="productId-{{ $item->product_id }}" name="product_ids[]">
                                    <input value="{{ $item->id }}" type="hidden" id = "product_row_id" name="product_row_ids[]">
                                    <input input value="{{$item->product_variant_id  ? $item->product_variant_id  : 'noid'}}" type="hidden" name="variant_ids[]" value="{{$item->product_variant_id  ? $item->product_variant_id  : ''}}">
                                    <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ bcadd($item->tax_percent, 0, 2) }}">
                                    <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="{{ bcadd($item->tax_amount, 0, 2) }}">
                                </td>

                                <td>
                                    <input value="{{ bcadd($item->quantity, 0, 2) }}" readonly name="sold_quantities[]" type="number" step="any" class="form-control text-center" id="sold_quantity">
                                </td>

                                <td>
                                    <b><span class="sold_unit">{{ $item->product->unit->name }}</span></b>
                                </td>

                                <td>
                                    <input name="sold_prices_inc_tax[]" type="hidden" id="sold_price_inc_tax" value="{{ bcadd($item->unit_price_inc_tax, 0, 2) }}">
                                    <b><span class="sold_unit_price_inc_tax">{{ bcadd($item->unit_price_inc_tax, 0, 2) }}</span> </b>
                                </td>

                                <td>
                                    <input value="{{ bcadd($item->subtotal, 0, 2) }}" name="sold_subtotals[]" type="hidden" id="sold_subtotal">
                                    <b><span class="sold_subtotal">{{ bcadd($item->subtotal, 0, 2) }}</span></b>
                                </td>

                                <td>
                                    <input value="0.00" required name="ex_quantities[]" type="number" step="any" class="form-control text-center" id="ex_quantity">
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
