@extends('layout.master')
@push('stylesheets')
    <style>
        b { font-weight: 500 !important; font-family: Arial, Helvetica, sans-serif; }
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .data_preloader { top: 2.3% }

        .selected_invoice { background-color: #645f61; color: #fff !important; }

        .invoice_search_result { position: absolute; width: 100%; border: 1px solid #E4E6EF; background: white; z-index: 1; padding: 3px; margin-top: 1px; }

        .invoice_search_result ul li { width: 100%; border: 1px solid lightgray; margin-top: 2px; }

        .invoice_search_result ul li a { color: #6b6262; font-size: 10px; display: block; padding: 0px 3px; }

        .invoice_search_result ul li a:hover { color: var(--white-color);  background-color: #ada9a9; }

        .selectProduct { background-color: #645f61; color: #fff !important; }

        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 95%; z-index: 9999999; padding: 0; left: 5%; display: none; border: 1px solid #706a6d; margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 0px 2px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 11px; padding: 2px 2px; display: block; border: 1px solid lightgray; margin: 2px 0px; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .element-body { overflow: initial !important; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Add Sales Return - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-shopping-cart"></span>
                    <h6>{{ __("Add Sales Return") }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            <form id="add_sales_return_form" action="{{ route('sales.returns.store') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Customers") }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select required name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="sale_invoice_id" autofocus>
                                                @foreach ($customerAccounts as $customerAccount)
                                                    <option data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name.'/'.$customerAccount->phone }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_customer_account_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __("Closing Balance") }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" class="form-control text-danger fw-bold" id="closing_balance" value="0.00" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Invoice ID") }}</b></label>
                                        <div class="col-8">
                                            <div style="position: relative;">
                                                <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control fw-bold" data-next="warehouse_id" placeholder="{{ __("Serach Sales Invoice ID") }}" autocomplete="off">
                                                <input type="hidden" name="sale_id" id="sale_id">

                                                <div class="invoice_search_result d-hide">
                                                    <ul id="invoice_list" class="list-unstyled"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (count($warehouses) > 0)

                                        <input name="warehouse_count" value="YES" type="hidden" />
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __("Warehouse") }}</b></label>
                                            <div class="col-8">
                                                <select required class="form-control" name="warehouse_id" id="warehouse_id" data-next="sale_account_id">
                                                    <option value="">{{ __("Select Warehouse") }}</option>
                                                    @foreach ($warehouses as $w)
                                                        <option data-warehouse_name="{{ $w->warehouse_name }}" data-warehouse_code="{{ $w->warehouse_code }}" value="{{ $w->id }}">
                                                            {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __("Stored Location") }}</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control" value="{{ $branchName }}" />
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Voucher No") }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="{{ __("Voucher No") }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __("Sales Ledger") }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="sale_account_id" class="form-control select2" id="sale_account_id" data-next="date">
                                                @foreach ($saleAccounts as $saleAccount)
                                                    <option value="{{ $saleAccount->id }}">
                                                        {{ $saleAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_sale_account_id"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Return Date") }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business__date_format']) }}" data-next="price_group_id" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __("Price Group") }}</b></label>
                                        <div class="col-8">
                                            <select name="price_group_id" class="form-control" id="price_group_id" data-next="search_product">
                                                <option value="">{{ __("Default Selling Price") }}</option>
                                                @foreach ($priceGroups as $priceGroup)
                                                    <option {{ $generalSettings['sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <section>
                        <div class="card mb-1 p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row g-xxl-4 align-items-end">
                                        <div class="col-xl-6">
                                            <div class="searching_area" style="position: relative;">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-barcode text-dark input_f"></i>
                                                        </span>
                                                    </div>

                                                    <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __("Search Product By Name/Code") }}" autocomplete="off">
                                                </div>

                                                <div class="select_area">
                                                    <ul id="list" class="variant_list_area"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-xxl-4 align-items-end">
                                        <div class="hidden_fields">
                                            <input type="hidden" id="e_unique_id">
                                            <input type="hidden" id="e_item_name">
                                            <input type="hidden" id="e_product_id">
                                            <input type="hidden" id="e_variant_id">
                                            <input type="hidden" id="e_tax_amount">
                                            <input type="hidden" id="e_unit_cost_inc_tax">
                                            <input type="hidden" id="e_unit_price_inc_tax">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __("Quantity") }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control w-60 fw-bold" id="e_return_quantity" placeholder="{{ __("Return Quantity") }}" value="0.00">
                                                <select id="e_unit_id" class="form-control w-40">
                                                    <option value="">{{ __("Unit") }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __("Unit Price(Exc. Tax)") }}</label>
                                            <input type="number" step="any" class="form-control fw-bold" id="e_unit_price_exc_tax" placeholder="{{ __("Unit Price(Exc. Tax)") }}" value="0.00">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __("Discount (Per Unit)") }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __("Discount (Per Unit)") }}" value="0.00">
                                                <input type="hidden" id="e_discount" value="0.00">
                                                <select id="e_discount_type" class="form-control">
                                                    <option value="1">{{ __("Fixed") }}(0.00)</option>
                                                    <option value="2">{{ __("Percentage") }}(%)</option>
                                                </select>
                                                <input type="hidden" id="e_discount_amount">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __("Vat/Tax") }}</label>
                                            <div class="input-group">
                                                <select id="e_tax_ac_id" class="form-control">
                                                    <option data-product_tax_percent="0.00" value="">{{ __("NoVat/Tax") }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="e_tax_type" class="form-control" tabindex="-1">
                                                    <option value="1">{{ __("Exclusive") }}</option>
                                                    <option value="2">{{ __("Inclusive") }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __("Subtotal") }}</label>
                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                        </div>

                                        <div class="col-xl-1 col-md-6">
                                            <div class="btn-box-2">
                                                <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">{{ __("Product") }}</th>
                                                                    <th class="text-start">{{ __("Unit Cost (Inc. Tax)") }}</th>
                                                                    <th class="text-start">{{ __("Sold Qty") }}</th>
                                                                    <th class="text-start">{{ __("Return Qty") }}</th>
                                                                    <th class="text-start">{{ __("Unit") }}</th>
                                                                    <th class="text-start">{{ __("Subtotal") }}</th>
                                                                    <th class="text-start"><i class="fas fa-minus text-white"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_item_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row g-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Total Item") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Total Qty") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Net Total Amount") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="net_total_amount" id="net_total_amount" class="form-control fw-bold" value="0" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Return Discount") }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input name="return_discount" type="number" class="form-control fw-bold" id="return_discount" value="0.00" data-next="return_discount_type">
                                                                <input name="return_discount_amount" type="number" step="any" class="d-none" id="return_discount_amount" value="0.00">
                                                                <select name="return_discount_type" class="form-control" id="return_discount_type" data-next="return_tax_ac_id">
                                                                    <option value="1">{{ __("Fixed") }}(0.00)</option>
                                                                    <option value="2">{{ __("Percentage") }}(%)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Return Tax") }}</b></label>

                                                        <div class="col-8">
                                                            <select name="return_tax_ac_id" class="form-control" id="return_tax_ac_id" data-next="note">
                                                                <option data-return_tax_percent="0.00" value="">{{ __("NoTax") }}</option>
                                                                @foreach ($taxAccounts as $taxAccount)
                                                                    <option data-return_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                        {{ $taxAccount->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input name="return_tax_percent" type="number" step="any" class="d-none" id="return_tax_percent" value="0.00">
                                                            <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="0.00">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class=" col-4"><b>{{ __("Return Note") }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="note" class="form-control" id="note" data-next="paying_amount" placeholder="{{ __("Return Note") }}" autocomplete="off">
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
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row g-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Total Return Amount") }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_return_amount" id="total_return_amount" class="form-control fw-bold" value="0.00" placeholder="{{ __("Total Return Amount") }}" tabindex="-1">
                                                    <input type="hidden" name="sale_ledger_amount" id="sale_ledger_amount">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Paying Amount") }}</b> <strong>>></strong></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Payment Method") }} <span class="text-danger">*</span></b> </label>
                                                <div class="col-8">
                                                    <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                        @foreach ($methods as $method)
                                                            <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}"
                                                                value="{{ $method ->id }}">{{ $method->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_payment_method_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Credit A/c') }} <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
                                                        @foreach ($accounts as $ac)
                                                            @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                                                @continue
                                                            @endif

                                                            <option value="{{ $ac->id }}">
                                                                @php
                                                                    $acNo = $ac->account_number ? ', A/c No : '.$ac->account_number : '';
                                                                    $bank = $ac?->bank ? ', Bank : '.$ac?->bank?->name : '';
                                                                @endphp
                                                                {{ $ac->name . $acNo . $bank}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class=" col-4"><b>{{ __("payment Note") }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save_and_print" placeholder="{{ __("payment Note") }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Current Balance') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="current_balance" class="form-control text-danger fw-bold" id="current_balance" value="0.00" placeholder="{{ __('0.00') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row justify-content-center mt-1">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __("Loading") }}...</span> </button>
                            <button type="submit" id="save_and_print" value="save_and_print" class="btn btn-success submit_button">{{ __("Save And Print") }}</button>
                            <button type="submit" id="save" value="save" class="btn btn-success submit_button">{{ __("Save") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sales.sales_return.js_partials.add_js')
@endpush
