<style>
    .input-group-text {padding: 0px 8px !important;}
    .input-group-prepend {background: white !important;}
</style>

<form id="update_opening_stock_form" action="{{ route('products.opening.stock.update') }}"
    method="POST">
    @csrf
    <div class="card mt-3">
        <div class="card-header">
            <p class="m-0"><b>@lang('menu.business_location') : {{ auth()->user()->branch ? auth()->user()->branch->name .'/'. auth()->user()->branch->branch_code : $generalSettings['business__shop_name'] }}</b> </p>
        </div>
        <div class="card_body">
            <div class="product_stock_table_area">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-white">@lang('menu.product_name')</th>
                                <th class="text-white">@lang('menu.quantity_remaining')</th>
                                <th class="text-white">@lang('menu.unit_cost_exc_tax')</th>
                                <th class="text-white">@lang('menu.sub_total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $pro)
                                @php
                                    $__v_id = $pro->v_id ? $pro->v_id : NULL;
                                    $os = DB::table('product_opening_stocks')
                                    ->where('branch_id', auth()->user()->branch_id)
                                    ->where('product_id', $pro->p_id)
                                    ->where('product_variant_id', $__v_id)->first();
                                @endphp

                                @if ($os)
                                    <tr>
                                        <td class="text">{{ $pro->p_name.' '.$pro->v_name }}</td>
                                        <td>
                                            <input type="hidden" name="product_ids[]" value="{{ $pro->p_id }}">
                                            <input type="hidden" name="variant_ids[]" value="{{ $pro->v_id ? $pro->v_id : 'noid' }}">

                                            <div class="input-group width-25 ml-2">
                                                <input type="number" step="any" name="quantities[]"
                                                    class="form-control" id="quantity"
                                                    value="{{ $os->quantity }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input_group_text_custom text-dark">{{ $pro->u_code }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="unit_costs_inc_tax[]"
                                                class="form-control" id="unit_cost_inc_tax"
                                                value="{{ $os->unit_cost_inc_tax }}">
                                        </td>

                                        <td class="text">
                                            <b><span class="span_subtotal">{{ $os->subtotal }}</span></b>
                                            <input type="hidden" id="subtotal" name="subtotals[]" value="{{ $os->subtotal }}">
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text">{{ $pro->p_name.' '.$pro->v_name }}</td>
                                        <td>
                                            <input type="hidden" name="product_ids[]" value="{{ $pro->p_id }}">
                                            <input type="hidden" name="variant_ids[]" value="{{ $pro->v_id ? $pro->v_id : 'noid' }}">

                                            <div class="input-group width-25 ml-2">
                                                <input type="number" step="any" name="quantities[]"
                                                    class="form-control" id="quantity"
                                                    value="0.00">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input_group_text_custom text-dark">
                                                        {{ $pro->u_code }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="unit_costs_inc_tax[]"
                                                class="form-control" id="unit_cost_inc_tax"
                                                value="{{ $pro->v_cost_inc_tax ? $pro->v_cost_inc_tax : $pro->p_cost_inc_tax }}">
                                        </td>

                                        <td class="text">
                                            <b><span class="span_subtotal">0.00</span></b>
                                            <input type="hidden" id="subtotal" name="subtotals[]" value="0.00">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
        </div>
    </div>
</form>