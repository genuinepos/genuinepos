@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute; width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px; padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        h6.collapse_table:hover {background: lightgray; padding: 3px; cursor: pointer;}
        .c-delete:focus {border: 1px solid gray;padding: 2px;}
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .checkbox_input_wrap {text-align: right;}
    </style>
@endpush

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-shopping-cart"></span>
                    <h6>@lang('menu.add_purchase_order')</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-lg-3 p-1">
            <form id="add_purchase_order_form" action="{{ route('purchases.order.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section>
                    <div class="form_element rounded mt-0 mb-lg-3 mb-1">

                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>@lang('menu.supplier')</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <select required name="supplier_id" class="form-control select2" id="supplier_id" data-next="pay_term_number">
                                                    <option value="">@lang('menu.select_supplier')</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option data-pay_term="{{ $supplier->pay_term }}" data-pay_term_number="{{ $supplier->pay_term_number }}" value="{{ $supplier->id }}">{{ $supplier->name.'/'.$supplier->phone }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text add_button" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                </div>
                                            </div>
                                            <span class="error error_supplier_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.curr_balance') </b></label>
                                        <div class="col-8">
                                            <input readonly type="text" id="current_balance" class="form-control fw-bold" value="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.order_id') </b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Purchase Order ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="order_id" id="order_id" class="form-control" data-next="warehouse_id" placeholder="@lang('menu.purchase_order_id')" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>@lang('menu.pay_term') </b> </label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control"
                                                id="pay_term_number" data-next="pay_term" placeholder="Number">
                                                <select name="pay_term" class="form-control" id="pay_term" data-next="date">
                                                    <option value="">@lang('menu.pay_term')</option>
                                                    <option value="1">@lang('menu.days')</option>
                                                    <option value="2">@lang('menu.months')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('menu.date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="date" class="form-control"
                                             id="date" value="{{ date($generalSettings['business__date_format']) }}" data-next="delivery_date" placeholder="dd-mm-yyyy" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('menu.delivery_date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="delivery_date" class="form-control"
                                             id="delivery_date" data-next="purchase_account_id" placeholder="{{ $generalSettings['business__date_format'] }}" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.purchase_ac') <span class="text-danger">*</span></b></label>
                                        <div class="col-8">
                                            <select name="purchase_account_id" class="form-control" id="purchase_account_id" data-next="search_product">
                                                @foreach ($purchaseAccounts as $purchaseAccount)
                                                    <option value="{{ $purchaseAccount->id }}">
                                                        {{ $purchaseAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_purchase_account_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card ps-1 pb-1 pe-1">
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="row align-items-end">
                                    <input type="hidden" id="e_unique_id">
                                    <input type="hidden" id="e_item_name">
                                    <input type="hidden" id="e_product_id">
                                    <input type="hidden" id="e_variant_id">
                                    <input type="hidden" id="e_tax_amount">
                                    <input type="hidden" id="e_unit_cost_with_discount">
                                    <input type="hidden" id="e_subtotal">
                                    <input type="hidden" id="e_unit_cost_inc_tax">

                                    <div class="col-xl-4 col-md-4">
                                        <div class="searching_area" style="position: relative;">
                                            <label class="fw-bold">@lang('menu.search_product')</label>
                                            <div class="input-group">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_product')" autofocus>

                                                @if (auth()->user()->can('product_add'))
                                                    <div class="input-group-prepend">
                                                        <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.quantity')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_unit" class="form-control w-40">
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.unit_cost_exc_tax')</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.discount')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_discount" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_discount_type" class="form-control w-40">
                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                <option value="2">@lang('menu.percentage')(%)</option>
                                            </select>
                                            <input type="hidden" id="e_discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.tax')</label>
                                        <div class="input-group">
                                            <select id="e_tax_percent" class="form-control w-50">
                                                <option value="">@lang('menu.no_tax')</option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->tax_percent }}">
                                                        {{ $tax->tax_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select id="e_tax_type" class="form-control w-50">
                                                <option value="1">@lang('menu.exclusive')</option>
                                                <option value="2">@lang('menu.inclusive')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.short_description')</label>
                                        <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
                                    </div>

                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __('Profit(%) & Selling Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" placeholder="@lang('menu.profit_margin')" autocomplete="off">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_selling_price" placeholder="@lang('menu.selling_price_exc_tax')" autocomplete="off">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.line_total')</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success me-2" id="add_item">@lang('menu.add')</a>
                                        <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">@lang('menu.reset')</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="sale-item-sec">
                                <div class="sale-item-inner">
                                    <div class="table-responsive">
                                        <table class="display data__table table-striped">
                                            <thead class="staky">
                                                <tr>
                                                    <th>@lang('menu.product')</th>
                                                    <th>@lang('menu.quantity')</th>
                                                    <th>@lang('menu.unit_cost_exc_tax')</th>
                                                    <th>@lang('menu.discount')</th>
                                                    <th>@lang('menu.unit_tax')</th>
                                                    <th>{{ __('Net Unit Cost (Inc. Tax)') }}</th>
                                                    <th>@lang('menu.line_total')</th>

                                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                        <th>@lang('menu.x_margin')(%)</th>
                                                        <th>@lang('menu.selling_price_exc_tax')</th>
                                                    @endif
                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_order_product_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row g-3 py-3">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">

                                                        <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_quantity') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_discount') </b></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                        <option value="1">@lang('menu.fixed')(0.00)</option>
                                                                        <option value="2">@lang('menu.percentage')(%)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="0.00" data-next="purchase_tax_percent">
                                                                </div>
                                                            </div>
                                                            <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_tax') </b><span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="purchase_tax_percent" class="form-control" id="purchase_tax_percent" data-next="shipment_charge">
                                                                <option value="0.00">@lang('menu.no_tax')</option>
                                                                @foreach ($taxes as $tax)
                                                                    <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input name="purchase_tax_amount" type="number" step="any" class="d-hide" id="purchase_tax_amount" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_cost') </b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00" data-next="shipment_details">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_details') </b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" data-next="purchase_note" placeholder="@lang('menu.shipment_details')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_note') </b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="order_note" id="order_note" class="form-control" data-next="paying_amount" placeholder="@lang('menu.order_note').">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.net_total_amount') </b>  {{ $generalSettings['business__currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.total_ordered_amount') </b>  {{ $generalSettings['business__currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_ordered_amount" id="total_ordered_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.paying_amount') </b> {{ $generalSettings['business__currency'] }} <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.payment_method')<span
                                                            class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option
                                                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                                        value="{{ $method ->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.credit_ac')<span
                                                            class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        @php
                                                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                                                                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                                                            $balance = ', BL : '.$account->balance;
                                                                        @endphp
                                                                        {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_account_id"></span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.order_due') </b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" class="form-control fw-bold" name="order_due" id="order_due" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.payment_note') </b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save_and_print" placeholder="@lang('menu.payment_note')" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row justify-content-center">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>@lang('menu.loading')...</span> </button>
                            <button type="submit" id="save_and_print" value="1" class="btn btn-sm btn-success submit_button">@lang('menu.save_print')</button>
                            <button type="submit" id="save" value="2" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_supplier')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_supplier_modal_body"></div>
            </div>
        </div>
    </div>

    <!--Add Product Modal-->
    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif
@endsection
@push('scripts')
    @include('purchases.orders.js_partials.purchaseOrderCreateJsScript')
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();

        $('select').on('select2:close', function (e) {

            var nextId = $(this).data('next');

            setTimeout(function () {

                $('#'+nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#'+nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e){

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                    $('#date').focus().select();
                    return;
                }

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 ||  $('#paying_amount').val() == '' )) {

                    $('#save_and_print').focus().select();
                    return;
                }

                $('#'+nextId).focus().select();
            }
        });
    </script>
@endpush