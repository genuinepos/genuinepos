@extends('layout.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .contract_info_area ul li strong {
                color: #495677
            }

            .account_summary_area .heading h5 {
                background: #0F3057;
                color: white
            }

            .contract_info_area ul li strong i {
                color: #495b77;
                font-size: 13px;
            }
        </style>
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6><strong>{{ $customer->name }}</strong></h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="ledger" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fas fa-scroll"></i> @lang('menu.ledger')
                            </a>

                            <a id="tab_btn" data-show="contract_info_area" class="btn btn-sm btn-primary tab_btn" href="#"><i class="fas fa-info-circle">
                                </i> {{ __('Contract Info') }}
                            </a>

                            <a id="tab_btn" data-show="sale" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> @lang('menu.sale')
                            </a>

                            @if (auth()->user()->can('sale_payment'))
                                <a id="tab_btn" data-show="payments" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="far fa-money-bill-alt"></i>@lang('menu.payments')
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="tab_contant ledger">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3">
                                @include('contacts.customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_customer_ledgers" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">

                                                {{-- @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                    <div class="col-lg-3 col-md-6">
                                                        <label><strong>@lang('menu.business_location') </strong></label>
                                                        <select name="branch_id" class="form-control submit_able select2" id="ledger_branch_id" autofocus>
                                                            <option value="">{{ __("All") }}</option>
                                                            <option value="NULL">
                                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                                            </option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>@lang('menu.voucher_type') </strong></label>
                                                    <select name="voucher_type" class="form-control submit_able select2" id="ledger_voucher_type" autofocus>
                                                        <option value="">{{ __("All") }}</option>
                                                        @foreach (App\Utils\CustomerUtil::voucherTypes() as $key => $type)
                                                            <option value="{{ $key }}">{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i>@lang('menu.print')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="ledger_table_area">
                                    <div class="table-responsive" id="payment_list_table">
                                        <table class="display data_tbl data__table ledger_table">
                                            <thead>
                                                <tr>
                                                <tr>
                                                    <th>@lang('menu.date')</th>
                                                    <th>{{ __('Particulars') }}</th>
                                                    <th>@lang('menu.business_location')</th>
                                                    <th>{{ __('Voucher/Invoice') }}</th>
                                                    <th>@lang('menu.debit')</th>
                                                    <th>@lang('menu.credit')</th>
                                                    <th>@lang('menu.running_balance')</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="4" class="text-white text-end">@lang('menu.total') : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                    <th id="debit" class="text-white text-end"></th>
                                                    <th id="credit" class="text-white text-end"></th>
                                                    <th class="text-white text-end">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant contract_info_area d-hide">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong>@lang('menu.customer_name') </strong></li>
                                    <li><span class="name">{{ $customer->name }}</span></li><br>
                                    <li><strong><i class="fas fa-map-marker-alt"></i> @lang('menu.address') </strong></li>
                                    <li><span class="address">{{ $customer->address }}</span></li><br>
                                    <li><strong><i class="fas fa-briefcase"></i> @lang('menu.business_name') </strong></li>
                                    <li><span class="business">{{ $customer->business_name }}</span></li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong><i class="fas fa-phone-square"></i> @lang('menu.phone')</strong></li>
                                    <li><span class="phone">{{ $customer->phone }}</span></li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong><i class="fas fa-info"></i> @lang('menu.tax_number')</strong></li>
                                    <li><span class="tax_number">{{ $customer->tax_number }}</span></li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li>
                                        <strong> @lang('menu.opening_balance') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="opening_balance">{{ App\Utils\Converter::format_in_bdt($customer->opening_balance) }}</span>
                                    </li>

                                    <li>
                                        <strong> @lang('menu.total_sale') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_sale">{{ App\Utils\Converter::format_in_bdt($customer->total_sale) }}</span>
                                    </li>

                                    <li>
                                        <strong> @lang('menu.total_return') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_return">{{ App\Utils\Converter::format_in_bdt($customer->total_return) }}</span>
                                    </li>

                                    <li>
                                        <strong> @lang('menu.total_less') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_less">{{ App\Utils\Converter::format_in_bdt($customer->total_less) }}</span>
                                    </li>

                                    <li>
                                        <strong> @lang('menu.total_paid') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_paid">{{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}</span>
                                    </li>

                                    <li>
                                        <strong> @lang('menu.total_due') : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_sale_due">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</span>
                                    </li>

                                    <li>
                                        <strong> {{ __('Total Returnable Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                        <span class="total_sale_return_due">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant sale d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                @include('contacts.customers.partials.account_summery_area_by_sales')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_customer_sales" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">

                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-lg-3 col-md-6">
                                                        <label><strong>@lang('menu.business_location') </strong></label>
                                                        <select name="branch_id" class="form-control submit_able select2" id="sale_branch_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="NULL">
                                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                                            </option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_sale_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="to_date" id="to_sale_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_sale_statement"><i class="fas fa-print"></i>@lang('menu.print')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table sales_table w-100">
                                            <thead>
                                                <tr>
                                                    <th>@lang('menu.action')</th>
                                                    <th>@lang('menu.date')</th>
                                                    <th>@lang('menu.invoice_id')</th>
                                                    <th>@lang('menu.business_location')</th>
                                                    <th>@lang('menu.customer')</th>
                                                    <th>@lang('menu.total_amount')</th>
                                                    <th>@lang('menu.total_paid')</th>
                                                    <th>@lang('menu.sell_due')</th>
                                                    <th>@lang('menu.return_amount')</th>
                                                    <th>@lang('menu.return_due')</th>
                                                    <th>@lang('menu.payment_status')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="5" class="text-end text-white">@lang('menu.total') </th>
                                                    <th class="text-end text-white" id="total_payable_amount"></th>
                                                    <th class="text-end text-white" id="paid"></th>
                                                    <th class="text-end text-white" id="due"></th>
                                                    <th class="text-end text-white" id="sale_return_amount"></th>
                                                    <th class="text-end text-white" id="sale_return_due"></th>
                                                    <th class="text-start text-white">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->can('sale_payment'))
                        <div class="tab_contant payments d-hide">
                            <div class="row">
                                <div class="col-sm-12 col-lg-3">
                                    @include('contacts.customers.partials.account_summery_area_by_payments')
                                </div>

                                <div class="col-sm-12 col-lg-9">
                                    <div class="account_summary_area">
                                        <div class="heading py-1">
                                            <h5 class="py-1 pl-1 text-center">@lang('menu.filter_area')</h5>
                                        </div>

                                        <div class="account_summary_table">
                                            <div class="row mt-2">
                                                <div class="col-md-9">
                                                    <div class="card pb-5">
                                                        <form id="filter_customer_payments" class="py-2 px-2 mt-2" method="get">
                                                            <div class="form-group row">
                                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <label><strong>@lang('menu.business_location') </strong></label>
                                                                        <select name="branch_id" class="form-control submit_able select2" id="payment_branch_id" autofocus>
                                                                            <option value="">@lang('menu.all')</option>
                                                                            <option value="NULL">
                                                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                                                            </option>
                                                                            @foreach ($branches as $branch)
                                                                                <option value="{{ $branch->id }}">
                                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif

                                                                <div class="col-lg-3 col-md-6">
                                                                    <label><strong>@lang('menu.from_date') </strong></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                        </div>
                                                                        <input type="text" name="p_from_date" id="payment_from_date" class="form-control" autocomplete="off">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3 col-md-6">
                                                                    <label><strong>@lang('menu.to_date') </strong></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                        </div>
                                                                        <input type="text" name="p_to_date" id="payment_to_date" class="form-control" autocomplete="off">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-3 col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label><strong></strong></label>
                                                                            <div class="input-group">
                                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mt-md-0 mt-2">
                                                    <div class="col-md-12 col-sm-12 col-lg-12 d-md-block d-flex gap-2">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <a href="{{ route('customers.payment', $customer->id) }}" id="add_payment" class="btn btn-sm btn-success"><i class="far fa-money-bill-alt text-white"></i> @lang('menu.receive')</a>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-md-2">
                                                            <div class="col-md-12">
                                                                <a class="btn btn-sm btn-success return_payment_btn" id="add_return_payment" href="{{ route('customers.return.payment', $customer->id) }}"><i class="far fa-money-bill-alt text-white"></i> @lang('menu.refund') </a>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-md-2">
                                                            <div class="col-md-12">
                                                                <a href="{{ route('customers.all.payment.print', $customer->id) }}" class="btn btn-sm btn-primary" id="print_payments"><i class="fas fa-print"></i>@lang('menu.print')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widget_content table_area">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table payments_table w-100">
                                                <thead>
                                                    <tr class="text-start">
                                                        <th class="text-start">@lang('menu.date')</th>
                                                        <th class="text-start">@lang('menu.voucher_no')</th>
                                                        <th class="text-start">@lang('menu.reference')</th>
                                                        <th class="text-start">@lang('menu.against_invoice')</th>
                                                        {{-- <th>Created By</th> --}}
                                                        <th class="text-start">@lang('menu.payment_status')</th>
                                                        <th class="text-start">@lang('menu.payment_type')</th>
                                                        <th class="text-start">@lang('menu.account')</th>
                                                        <th class="text-endx">@lang('menu.less_amount')</th>
                                                        <th class="text-end">@lang('menu.paid_amount')</th>
                                                        <th class="text-start">@lang('menu.action')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th class="text-end text-white" colspan="7">@lang('menu.total') </th>
                                                        <th class="text-end text-white" id="less_amount"></th>
                                                        <th class="text-end text-white" id="amount"></th>
                                                        <th class="text-start text-white">---</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="payment_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <!-- Details Modal -->
    <div id="sale_details"></div>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content"></div>
        </div>
    </div>

    @if (auth()->user()->can('sale_payment'))
        <!--Payment View modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_list')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_view_modal_body"> </div>
                </div>
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <!--Payment list modal-->
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_details')(<span class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area"></div>

                        <div class="row">
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled">
                                    <li class="mt-3" id="payment_attachment"></li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled">
                                    <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary float-end">@lang('menu.print')</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        //Get customer Ledgers by yajra data table
        var ledger_table = $('.ledger_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary'
                },
            ],

            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('contacts.customer.ledger.list', $customer->id) }}",
                "data": function(d) {
                    d.branch_id = $('#ledger_branch_id').val();
                    d.voucher_type = $('#ledger_voucher_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [{
                    data: 'date',
                    name: 'customer_ledgers.report_date'
                },
                {
                    data: 'particulars',
                    name: 'particulars'
                },
                {
                    data: 'b_name',
                    name: 'branches.name'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'debit',
                    name: 'debit',
                    className: 'text-end'
                },
                {
                    data: 'credit',
                    name: 'credit',
                    className: 'text-end'
                },
                {
                    data: 'running_balance',
                    name: 'running_balance',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                var debit = sum_table_col($('.data_tbl'), 'debit');
                $('#debit').text(bdFormat(debit));

                var credit = sum_table_col($('.data_tbl'), 'credit');
                $('#credit').text(bdFormat(credit));
                $('.data_preloader').hide();
            }
        });

        //Get customer Sales by yajra data table
        var sales_table = $('.sales_table').DataTable({
            "processing": true,
            "serverSide": true,
            // aaSorting: [[3, 'asc']],

            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            "ajax": {
                "url": "{{ route('contacts.customer.view', $customerId) }}",
                "data": function(d) {
                    d.branch_id = $('#sale_branch_id').val();
                    d.from_date = $('#from_sale_date').val();
                    d.to_date = $('#to_sale_date').val();
                }
            },

            columnDefs: [{
                "targets": [0, 10],
                "orderable": false,
                "searchable": false
            }],

            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'invoice_id',
                    name: 'invoice_id'
                },
                {
                    data: 'from',
                    name: 'branches.name'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'total_payable_amount',
                    name: 'total_payable_amount',
                    className: 'text-end'
                },
                {
                    data: 'paid',
                    name: 'paid',
                    className: 'text-end'
                },
                {
                    data: 'due',
                    name: 'due',
                    className: 'text-end'
                },
                {
                    data: 'sale_return_amount',
                    name: 'sale_return_amount',
                    className: 'text-end'
                },
                {
                    data: 'sale_return_due',
                    name: 'sale_return_due',
                    className: 'text-end'
                },
                {
                    data: 'paid_status',
                    name: 'paid_status'
                },
            ],
            fnDrawCallback: function() {

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));

                var sale_return_amount = sum_table_col($('.data_tbl'), 'sale_return_amount');
                $('#sale_return_amount').text(bdFormat(sale_return_amount));

                var sale_return_due = sum_table_col($('.data_tbl'), 'sale_return_due');
                $('#sale_return_due').text(bdFormat(sale_return_due));

                $('.data_preloader').hide();
            }
        });

        @if (auth()->user()->can('sale_payment'))

            var payments_table = $('.payments_table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                dom: "lBfrtip",
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-primary'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> Pdf',
                        className: 'btn btn-primary'
                    },
                ],

                "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],

                "ajax": {
                    "url": "{{ route('customers.all.payment.list', $customer->id) }}",
                    "data": function(d) {
                        d.branch_id = $('#payment_branch_id').val();
                        d.p_from_date = $('#payment_from_date').val();
                        d.p_to_date = $('#payment_to_date').val();
                    }
                },

                columnDefs: [{
                    "targets": [3, 4, 5, 6],
                    "orderable": false,
                    "searchable": false
                }],

                columns: [{
                        data: 'date',
                        name: 'customer_ledgers.date'
                    },
                    {
                        data: 'voucher_no',
                        name: 'customer_payments.voucher_no'
                    },
                    {
                        data: 'reference',
                        name: 'customer_payments.reference'
                    },
                    {
                        data: 'against_invoice',
                        name: 'sales.invoice_id'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'account',
                        name: 'account'
                    },
                    {
                        data: 'less_amount',
                        name: 'customer_payments.less_amount',
                        className: 'text-end'
                    },
                    {
                        data: 'amount',
                        name: 'customer_ledgers.amount',
                        className: 'text-end'
                    },
                    {
                        data: 'action'
                    },
                ],
                fnDrawCallback: function() {

                    var amount = sum_table_col($('.data_tbl'), 'amount');
                    $('#amount').text(bdFormat(amount));

                    var less_amount = sum_table_col($('.data_tbl'), 'less_amount');
                    $('#less_amount').text(bdFormat(less_amount));
                    $('.data_preloader').hide();
                }
            });
        @endif

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {

                if (parseFloat($(this).find('.' + class_name).data('value'))) {

                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        var filterObj = {
            branch_id: null,
            from_date: null,
            to_date: null,
        };

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_customer_ledgers', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            ledger_table.ajax.reload();

            filterObj = {
                branch_id: $('#ledger_branch_id').val(),
                from_date: $('.from_date').val(),
                to_date: $('.to_date').val(),
            };

            var data = getCustomerAmountsBranchWise(filterObj, 'ledger_', false);
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_customer_sales', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            sales_table.ajax.reload();

            filterObj = {
                branch_id: $('#sale_branch_id').val(),
                from_date: $('#from_sale_date').val(),
                to_date: $('#to_sale_date').val(),
            };

            var data = getCustomerAmountsBranchWise(filterObj, 'sales_', false);
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_customer_payments', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            payments_table.ajax.reload();

            filterObj = {
                branch_id: $('#payment_branch_id').val(),
                from_date: $('#payment_from_date').val(),
                to_date: $('#payment_to_date').val(),
            };

            var data = getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();

            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#sale_details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        });

        // Print Packing slip
        $(document).on('click', '#print_packing_slip', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });

        // Show change status modal and pass actual link in the change status form
        $(document).on('click', '#edit_shipment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('.data_preloader').hide();
                    $('#edit_shipment_modal_content').html(data);
                    $('#editShipmentModal').modal('show');
                }
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.data_tbl').DataTable().ajax.reload();
                    toastr.error(data);

                    var filterObj = {
                        branch_id: $('#sale_branch_id').val(),
                        from_date: $('#from_sale_date').val(),
                        to_date: $('#to_sale_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'sales_', false);

                    filterObj = {
                        branch_id: $('#payment_branch_id').val(),
                        from_date: $('#payment_from_date').val(),
                        to_date: $('#payment_to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);

                    filterObj = {
                        branch_id: $('#ledger_branch_id').val(),
                        from_date: $('.from_date').val(),
                        to_date: $('.to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'ledger_', false);
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();

            var body = $('.sale_print_template').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#add_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#paymentModal').html(data);
                    $('#paymentModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('click', '#add_return_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Pay Return Amount');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#paymentModal').html(data);
                    $('#paymentModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        // //Show payment view modal with data
        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(date) {

                    $('#payment_view_modal_body').html(date);
                    $('#paymentViewModal').modal('show');
                }
            });
        });

        // // show payment edit modal with data
        $(document).on('click', '#edit_payment', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#paymentModal').html(data);
                    $('#paymentModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Return Payment');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#paymentModal').html(data);
                    $('#paymentModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(date) {

                    $('.payment_details_area').html(date);
                    $('#paymentDetailsModal').modal('show');
                }
            });
        });

        // Print single payment details
        $('#print_payment').on('click', function(e) {
            e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.header_area').html();
            var footer = $('.footer_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete_payment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);
            var url = $(this).attr('href');

            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#payment_deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            })
        });

        //data delete by ajax
        $(document).on('submit', '#payment_deleted_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.data_tbl').DataTable().ajax.reload();
                    $('#paymentViewModal').modal('hide');
                    toastr.error(data);

                    var filterObj = {
                        branch_id: $('#payment_branch_id').val(),
                        from_date: $('#payment_from_date').val(),
                        to_date: $('#payment_to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);

                    filterObj = {
                        branch_id: $('#ledger_branch_id').val(),
                        from_date: $('.from_date').val(),
                        to_date: $('.to_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'ledger_', false);

                    filterObj = {
                        branch_id: $('#sale_branch_id').val(),
                        from_date: $('#from_sale_date').val(),
                        to_date: $('#to_sale_date').val(),
                    };

                    getCustomerAmountsBranchWise(filterObj, 'sales_', false);
                }
            });
        });


        //Print Customer ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('contacts.customer.ledger.print', $customerId) }}";

            var branch_id = $('#ledger_branch_id').val();
            var voucher_type = $('#ledger_voucher_type').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    voucher_type,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });

        //Print purchase Payment report
        $(document).on('click', '#print_sale_statement', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.sale.statement.print') }}";

            var branch_id = $('#sale_branch_id').val();
            var customer_id = "{{ $customer->id }}";
            var from_date = $('.from_sale_date').val();
            var to_date = $('.to_sale_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    customer_id,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                    });
                }
            });
        });

        //Print Ledger
        $(document).on('click', '#print_payments', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var type = $('#type').val();
            var p_from_date = $('.p_from_date').val();
            var p_to_date = $('.p_to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    type,
                    p_from_date,
                    p_to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker2'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_sale_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_sale_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('payment_from_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('payment_to_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>

    <script>
        function getCustomerAmountsBranchWise(filterObj, showPrefix = 'ledger', is_show_all = true) {

            $.ajax({
                url: "{{ route('contacts.customer.amounts.branch.wise', $customer->id) }}",
                type: 'get',
                data: filterObj,
                success: function(data) {
                    var keys = Object.keys(data);

                    keys.forEach(function(val) {

                        if (is_show_all) {

                            $('.' + val).html(bdFormat(data[val]));
                        } else {

                            $('#' + showPrefix + val).html(bdFormat(data[val]));
                        }
                    });

                    $('#card_total_due').val(data['total_sale_due']);
                }
            });
        }

        getCustomerAmountsBranchWise(filterObj);
    </script>
@endpush
