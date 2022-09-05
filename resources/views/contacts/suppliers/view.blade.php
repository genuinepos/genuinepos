@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
<style>
    .contract_info_area ul li strong{color:#495677}
    .account_summary_area .heading h5{ background:#0F3057; color:white}
    .contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
    .account_summary_area {margin-bottom: -18px;}
</style>

<div class="body-woaper">
    <div class="container-fluid">
        <!--begin::Container-->
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-people-arrows"></span>
                            <h6><strong>{{ $supplier->name }}</strong></h6>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                    </div>
                </div>
           
                <div class="card">
                    <div class="card-body">
                        <div class="tab_list_area">
                            <ul class="list-unstyled">
                                <li>
                                    <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                        <i class="fas fa-scroll"></i> Ledger
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#">
                                        <i class="fas fa-info-circle"></i> Contract Info
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="purchases" class="purchases tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> Purchases
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="uncompleted_orders" class="uncompleted_orders tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> Purchase Orders
                                    </a>
                                </li>

                                @if (auth()->user()->permission->purchase['purchase_payment'] == '1') 
                                    <li>
                                        <a id="tab_btn" data-show="payments" class="tab_btn" href="#">
                                            <i class="far fa-money-bill-alt"></i> Payments
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="tab_contant ledger">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    @include('contacts.suppliers.partials.account_summery_area_by_ledger')
                                </div>

                                <div class="col-md-7 col-sm-12 col-lg-8">
                                    <div class="account_summary_area">
                                        <div class="heading">
                                            <h5 class="py-1 pl-1 text-center">Filter Area</h5>
                                        </div>

                                        <div class="account_summary_table">
                                            <form id="filter_supplier_ledgers" method="get" class="px-2">
                                                <div class="form-group row mt-4">
                                                    @if ($addons->branches == 1)

                                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)

                                                            <div class="col-md-3">
                                                                <label><strong>Business Location :</strong></label>
                                                                <select name="branch_id" class="form-control submit_able"
                                                                    id="ledger_branch_id" autofocus>
                                                                    <option value="">All</option>
                                                                    <option value="NULL">
                                                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                                    </option>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch->id }}">
                                                                            {{ $branch->name . '/' . $branch->branch_code }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @else
                                                    
                                                        <input type="hidden" name="branch_id" id="ledger_branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}">
                                                    @endif

                                                    <div class="col-md-3">
                                                        <label><strong>From Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="from_date" id="ledger_from_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label><strong>To Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="to_date" id="ledger_to_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <label><strong></strong></label>
                                                                <div class="input-group">
                                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-5 mt-3">
                                                                <a href="#" class="btn btn-sm btn-primary float-end mt-2" id="print_ledger"><i class="fas fa-print"></i> Print</a>
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
                                
                                <div class="data_preloader d-none">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>

                                <div class="col-md-12">
                                    <div class="ledger_list_table">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table ledger_table w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Particulars</th>
                                                        <th>Business Location</th>
                                                        <th>Voucher/P.Invoice</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Running Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="3" class="text-white text-end">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
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

                        <div class="tab_contant contract_info_area d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled"><br>
                                        <li><strong>Supplier Name :</strong></li>
                                        <li>{{ $supplier->name }}</li><br>
                                        <li><strong><i class="fas fa-map-marker-alt"></i> Address</strong></li>
                                        <li>{{ $supplier->address }}</li><br>
                                        <li><strong><i class="fas fa-briefcase"></i> Business Name</strong></li>
                                        <li>{{ $supplier->business_name }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                        <li>{{ $supplier->phone }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-info"></i> Tex Number</strong></li>
                                        <li><span class="tax_number">{{ $supplier->tax_number }}</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong> Total Purchase : </strong> 
                                        </li>

                                        <li>
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                            <span class="total_purchase">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</span>
                                        </li>

                                        <li>
                                            <strong> Total Paid : </strong> 
                                        </li>

                                        <li>
                                            <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                            <span class="total_paid">{{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}</span>
                                        </li>

                                        <li>
                                            <strong> Total Less : </strong>
                                        </li>

                                        <li>
                                            <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                            <span class="total_less">{{ App\Utils\Converter::format_in_bdt($supplier->total_less) }}</span>
                                        </li>

                                        <li>
                                            <strong> Total Purchase Due :</strong> 
                                        </li>

                                        <li>
                                            <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                            <span class="total_purchase_due">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant purchases d-none">

                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    @include('contacts.suppliers.partials.account_summery_area_by_purchases')
                                </div>

                                <div class="col-md-7 col-sm-12 col-lg-8">
                                    <div class="account_summary_area">
                                        <div class="heading">
                                            <h5 class="py-1 pl-1 text-center">Filter Area</h5>
                                        </div>

                                        <div class="account_summary_table">
                                            <form id="filter_supplier_purchases" method="get" class="px-2">
                                                <div class="form-group row mt-4">
                                                    @if ($addons->branches == 1)
                                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                            <div class="col-md-3">
                                                                <label><strong>Business Location :</strong></label>
                                                                <select name="branch_id" class="form-control submit_able"
                                                                    id="purchase_branch_id" autofocus>
                                                                    <option value="">All</option>
                                                                    <option value="NULL">
                                                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                                    </option>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch->id }}">
                                                                            {{ $branch->name . '/' . $branch->branch_code }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <div class="col-md-3">
                                                        <label><strong>From Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="from_date" id="purchase_from_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label><strong>To Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="to_date" id="purchase_to_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label><strong></strong></label>
                                                                <div class="input-group">
                                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 mt-1">
                                                                <a href="#" class="btn btn-sm btn-primary float-end mt-4" id="print_purchase_statements"><i class="fas fa-print"></i> Print</a>
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
                                    <div class="widget_content table_area">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table purchase_table w-100">
                                                <thead>
                                                    <tr class="text-left">
                                                        <th>Actions</th>
                                                        <th>Date</th>
                                                        <th>Reference ID</th>
                                                        <th>Purchase From</th>
                                                        <th>Supplier</th>
                                                        <th>Purchase Status</th>
                                                        <th>Payment Status</th>
                                                        <th>Grand Total</th>
                                                        <th>Paid</th>
                                                        <th>Payment Due</th>
                                                        <th>Return Amount</th>
                                                        <th>Return Due</th>
                                                        <th>Created By</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="7" class="text-end text-white">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                        <th class="text-start text-white" id="total_purchase_amount"></th>
                                                        <th class="text-start text-white" id="paid"></th>
                                                        <th class="text-start text-white" id="due"></th>
                                                        <th class="text-start text-white" id="return_amount"></th>
                                                        <th class="text-start text-white" id="return_due"></th>
                                                        <th class="text-start text-white">---</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <form id="deleted_form" action="" method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant uncompleted_orders d-none">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-lg-4">
                                    @include('contacts.suppliers.partials.account_summery_area_by_purchase_order')
                                </div>

                                <div class="col-md-7 col-sm-12 col-lg-8">
                                    <div class="account_summary_area">
                                        <div class="heading">
                                            <h5 class="py-1 pl-1 text-center">Filter Area</h5>
                                        </div>

                                        <div class="account_summary_table">
                                            <form id="filter_supplier_orders" method="get" class="px-2">
                                                <div class="form-group row mt-4">
                                                    @if ($addons->branches == 1)
                                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                            <div class="col-md-3">
                                                                <label><strong>Business Location :</strong></label>
                                                                <select name="branch_id" class="form-control submit_able"
                                                                    id="order_branch_id" autofocus>
                                                                    <option value="">All</option>
                                                                    <option value="NULL">
                                                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                                    </option>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch->id }}">
                                                                            {{ $branch->name . '/' . $branch->branch_code }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <div class="col-md-3">
                                                        <label><strong>From Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="from_date" id="order_from_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label><strong>To Date :</strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                            </div>
                                                            <input type="text" name="to_date" id="order_to_date" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label><strong></strong></label>
                                                                <div class="input-group">
                                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                                </div>
                                                            </div>

                                                            {{-- <div class="col-md-6 mt-1">
                                                                <a href="#" class="btn btn-sm btn-primary float-end mt-4" id="print_purchase_orderss"><i class="fas fa-print"></i> Print</a>
                                                            </div> --}}
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
                                    <div class="widget_content table_area">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table uncompleted_orders_table w-100">
                                                <thead>
                                                    <tr >
                                                        <th class="text-start">Actions</th>
                                                        <th class="text-start">Date</th>
                                                        <th class="text-start">Order ID</th>
                                                        <th class="text-start">Purchase From</th>
                                                        <th class="text-start">Supplier</th>
                                                        <th class="text-start">Created By</th>
                                                        <th class="text-start">Receiving Status</th>
                                                        <th class="text-end">Ordered Qty</th>
                                                        <th class="text-end">Received Qty</th>
                                                        <th class="text-end">Pending Qty</th>
                                                        <th class="text-end">Grand Total</th>
                                                        <th class="text-end">Paid</th>
                                                        <th class="text-end">Due</th>
                                                        <th class="text-end">Payment Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="7" class="text-end text-white">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                        <th class="text-start text-white" id="po_qty"></th>
                                                        <th class="text-start text-white" id="po_received_qty"></th>
                                                        <th class="text-start text-white" id="po_pending_qty"></th>
                                                        <th class="text-start text-white" id="po_total_purchase_amount"></th>
                                                        <th class="text-start text-white" id="po_paid"></th>
                                                        <th class="text-start text-white" id="po_due"></th>
                                                        <th class="text-start text-white">---</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <form id="deleted_form" action="" method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->permission->purchase['purchase_payment'] == '1') 
                            <div class="tab_contant payments d-none">

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-lg-4">
                                        @include('contacts.suppliers.partials.account_summery_area_payments')
                                    </div>
    
                                    <div class="col-md-8 col-sm-12 col-lg-8">
                                        <div class="account_summary_area">
                                            <div class="heading">
                                                <h5 class="py-1 pl-1 text-center">Filter Area</h5>
                                            </div>
    
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="card mt-3 pb-5">
                                                        <form id="filter_supplier_payments" class="py-2 px-2 mt-2" method="get">
                                                            <div class="form-group row">
                                                                @if ($addons->branches == 1)

                                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)

                                                                        <div class="col-md-3">
                                                                            <label><strong>Business Location :</strong></label>
                                                                            <select name="branch_id" class="form-control submit_able"
                                                                                id="payments_branch_id" autofocus>
                                                                                <option value="">All</option>
                                                                                <option value="NULL">
                                                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                                                </option>
                                                                                @foreach ($branches as $branch)
                                                                                    <option value="{{ $branch->id }}">
                                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                
                                                                    <input type="hidden" name="branch_id" id="ledger_branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}">
                                                                @endif
            
                                                                <div class="col-md-3">
                                                                    <label><strong>From Date :</strong></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                        </div>
                                                                        <input type="text" name="p_from_date" id="payments_from_date" class="form-control"autocomplete="off">
                                                                    </div>
                                                                </div>
            
                                                                <div class="col-md-3">
                                                                    <label><strong>To Date :</strong></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                        </div>
                                                                        <input type="text" name="p_to_date" id="payments_to_date" class="form-control" autocomplete="off">
                                                                    </div>
                                                                </div>
            
                                                                <div class="col-md-3">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label><strong></strong></label>
                                                                            <div class="input-group">
                                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <a href="{{ route('suppliers.payment', $supplier->id) }}" id="add_payment" class="btn btn-success"><i class="far fa-money-bill-alt text-white"></i> PAY</a>
                                                        </div>
                                                    </div>
            
                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <a class="btn btn-success return_payment_btn" id="add_payment" href="{{ route('suppliers.return.payment', $supplier->id) }}"><i class="far fa-money-bill-alt text-white"></i>Refund </a>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <a href="{{ route('suppliers.all.payment.print', $supplier->id) }}" class="btn btn-sm btn-primary" id="print_payments"><i class="fas fa-print"></i> Print</a>
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
                                                            <th class="text-start">Date</th>
                                                            <th class="text-start">Voucher No</th>
                                                            <th class="text-start">Reference</th>
                                                            <th class="text-start">Against Invoice</th>
                                                            {{-- <th>Created By</th> --}}
                                                            <th class="text-start">Payment Status</th>
                                                            <th class="text-start">Payment Type</th>
                                                            <th class="text-start">Account</th>
                                                            <th class="text-end">Less Amount</th>
                                                            <th class="text-end">Paid Amount</th>
                                                            <th class="text-start">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="bg-secondary">
                                                            <th class="text-end text-white" colspan="7">Total : </th>
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
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="payment_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
        <!--Payment list modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_list_modal_body"></div>
                </div>
            </div>
        </div>
        <!--Payment list modal-->

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <!--Add Payment modal-->
        
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content payment_details_contant">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area"></div>

                        <div class="row">
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1" id="payment_attachment"></li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1">
                                        {{-- <a href="" id="print_payment" class="btn btn-sm btn-primary">Print</a> --}}
                                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                        <button type="submit" id="print_payment" class="c-btn me-0 button-success">Print</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="purchase_details"></div>
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        @if (Session::has('successMsg'))

            var dataName = "{{ session('successMsg')[1] }}";

            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $('.'+dataName).data('show');
            $('.' + show_content).show();
            $('.'+dataName).addClass('tab_active');
        @endif
    </script>

    <script>
        var ledger_table = $('.ledger_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching" : false,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary'},
                {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
            ],

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

            "ajax": {
                "url": "{{ route('contacts.supplier.ledgers', $supplier->id) }}",
                "data": function(d) {
                    d.branch_id = $('#ledger_branch_id').val();
                    d.from_date = $('#ledger_from_date').val();
                    d.to_date = $('#ledger_to_date').val();
                }
            },

            columns: [
                {data: 'date', name: 'supplier_ledgers.report_date'},
                {data: 'particulars', name: 'particulars'},
                {data: 'b_name', name: 'branches.name'},
                {data: 'voucher_no', name: 'voucher_no'},
                {data: 'debit', name: 'debit', className: 'text-end'},
                {data: 'credit', name: 'credit', className: 'text-end'},
                {data: 'running_balance', name: 'running_balance', className: 'text-end'},
            ],fnDrawCallback: function() {

                var debit = sum_table_col($('.data_tbl'), 'debit');
                $('#debit').text(bdFormat(debit));

                var credit = sum_table_col($('.data_tbl'), 'credit');
                $('#credit').text(bdFormat(credit));
                $('.data_preloader').hide();
            }
        });

        var table = $('.purchase_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

            "ajax": {
                "url": "{{ route('contacts.supplier.view', $supplierId) }}",
                "data": function(d) {
                    d.branch_id = $('#purchase_branch_id').val();
                    d.from_date = $('#purchase_from_date').val();
                    d.to_date = $('#purchase_to_date').val();
                }
            },

            columnDefs: [{
                "targets": [0, 5, 6],
                "orderable": false,
                "searchable": false
            }],

            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'status', name: 'status'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'total_purchase_amount', name: 'total_purchase_amount'},
                {data: 'paid', name: 'paid'},
                {data: 'due', name: 'due'},
                {data: 'return_amount', name: 'purchase_return_amount'},
                {data: 'return_due', name: 'purchase_return_due'},
                {data: 'created_by', name: 'created_by.name'},
            ],fnDrawCallback: function() {

                var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                $('#total_purchase_amount').text(bdFormat(total_purchase_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));

                var return_amount = sum_table_col($('.data_tbl'), 'return_amount');
                $('#return_amount').text(bdFormat(return_amount));

                var return_due = sum_table_col($('.data_tbl'), 'return_due');
                $('#return_due').text(bdFormat(return_due));

                $('#purchase_preloader').hide();
            }
        });

        var table = $('.uncompleted_orders_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            
            "ajax": {
                "url": "{{ route('suppliers.uncompleted.orders', $supplierId) }}",
                "data": function(d) {
                    d.branch_id = $('#order_branch_id').val();
                    d.from_date = $('#order_from_date').val();
                    d.to_date = $('#order_to_date').val();
                }
            },

            columnDefs: [{
                "targets": [0, 5, 13],
                "orderable": false,
                "searchable": false
            }],

            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'created_by', name: 'created_by.name'},
                {data: 'po_receiving_status', name: 'po_receiving_status'},
                {data: 'po_qty', name: 'po_qty', className: 'text-end'},
                {data: 'po_received_qty', name: 'po_received_qty', className: 'text-end'},
                {data: 'po_pending_qty', name: 'po_pending_qty', className: 'text-end'},
                {data: 'total_purchase_amount', name: 'total_purchase_amount', className: 'text-end'},
                {data: 'paid', name: 'paid', className: 'text-end'},
                {data: 'due', name: 'due', className: 'text-end'},
                {data: 'payment_status', name: 'payment_status'},

            ],fnDrawCallback: function() {

                var po_qty = sum_table_col($('.data_tbl'), 'po_qty');
                $('#po_qty').text(bdFormat(po_qty));

                var po_received_qty = sum_table_col($('.data_tbl'), 'po_received_qty');
                $('#po_received_qty').text(bdFormat(po_received_qty));

                var po_pending_qty = sum_table_col($('.data_tbl'), 'po_pending_qty');
                $('#po_pending_qty').text(bdFormat(po_pending_qty));

                var total_purchase_amount = sum_table_col($('.data_tbl'), 'po_total_purchase_amount');
                $('#po_total_purchase_amount').text(bdFormat(total_purchase_amount));

                var paid = sum_table_col($('.data_tbl'), 'po_paid');
                $('#po_paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'po_due');
                $('#po_due').text(bdFormat(due));

                $('#order_preloader').hide();
            }
        });

        @if (auth()->user()->permission->purchase['purchase_payment'] == '1')

            var payments_table = $('.payments_table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching" : true,
                dom: "lBfrtip",
                buttons: [
                    {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary'},
                    {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
                ],

                "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

                "ajax": {
                    "url": "{{ route('suppliers.all.payment.list', $supplier->id) }}",
                    "data": function(d) {
                        d.branch_id = $('#payments_branch_id').val();
                        d.p_from_date = $('#payments_from_date').val();
                        d.p_to_date = $('#payments_to_date').val();
                    }
                },

                columnDefs: [{
                    "targets": [3, 4, 5, 6],
                    "orderable": false,
                    "searchable": false
                }],

                columns: [
                    {data: 'date', name: 'supplier_ledgers.date'},
                    {data: 'voucher_no', name: 'supplier_payments.voucher_no'},
                    {data: 'reference', name: 'supplier_payments.reference'},
                    {data: 'against_invoice', name: 'purchases.invoice_id'},
                    {data: 'type', name: 'type'},
                    {data: 'method', name: 'method'},
                    {data: 'account', name: 'account'},
                    {data: 'less_amount', name: 'supplier_payments.less_amount', className: 'text-end'},
                    {data: 'amount', name: 'supplier_ledgers.amount', className: 'text-end'},
                    {data: 'action'},
                ],fnDrawCallback: function() {

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
            branch_id : null,
            from_date : null,
            to_date : null,
        };

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_supplier_ledgers', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            ledger_table.ajax.reload();

            filterObj = {
                branch_id : $('#ledger_branch_id').val(),
                from_date : $('#ledger_from_date').val(),
                to_date : $('#ledger_to_date').val(),
            };

            getSupplierAmountsBranchWise(filterObj, 'ledger_', false);
        });

         //Submit filter form by select input changing
         $(document).on('submit', '#filter_supplier_purchases', function (e) {
            e.preventDefault();

            $('#purchase_preloader').show();
            $('.purchase_table').DataTable().ajax.reload();

            filterObj = {
                branch_id : $('#purchase_branch_id').val(),
                from_date : $('#purchase_from_date').val(),
                to_date : $('#purchase_to_date').val(),
            };

            getSupplierAmountsBranchWise(filterObj, 'purchase_', false);
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_supplier_orders', function (e) {
            e.preventDefault();

            $('#order_preloader').show();
            $('.uncompleted_orders_table').DataTable().ajax.reload();

            filterObj = {
                branch_id : $('#order_branch_id').val(),
                from_date : $('#order_from_date').val(),
                to_date : $('#order_to_date').val(),
            };

            getSupplierAmountsBranchWise(filterObj, 'purchase_order_', false);
        });

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_supplier_payments', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            payments_table.ajax.reload();

            filterObj = {
                branch_id : $('#payments_branch_id').val(),
                from_date : $('#payments_from_date').val(),
                to_date : $('#payments_to_date').val(),
            };

            getSupplierAmountsBranchWise(filterObj, 'payments_', false);
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
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#purchase_table_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('#purchase_details').html(data);
                    $('#purchase_table_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {$('#deleted_form').submit();}
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {console.log('Deleted canceled.');} 
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
                        branch_id : $('#purchase_branch_id').val(),
                        from_date : $('#purchase_from_date').val(),
                        to_date : $('#purchase_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'purchase_', false);

                    filterObj = {
                        branch_id : $('#order_branch_id').val(),
                        from_date : $('#order_from_date').val(),
                        to_date : $('#order_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'purchase_order_', false);

                    filterObj = {
                        branch_id : $('#payments_branch_id').val(),
                        from_date : $('#payments_from_date').val(),
                        to_date : $('#payments_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'payments_', false);

                    filterObj = {
                        branch_id : $('#ledger_branch_id').val(),
                        from_date : $('#ledger_from_date').val(),
                        to_date : $('#ledger_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'ledger_', false);
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();
            var body = $('.purchase_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });
     
        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                }
            });
        });

        $(document).on('click', '#add_return_payment', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                }
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('#purchase_table_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                }
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $.ajax({
                url : url,
                type:'get',
                success:function(data){

                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                }
            });
        });

        // //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
            e.preventDefault();
            $('#purchase_table_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(date){

                    $('#payment_list_modal_body').html(date);
                    $('#paymentViewModal').modal('show');
                }
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(date){

                    $('.payment_details_area').html(date);
                    $('#paymentDetailsModal').modal('show');
                }
            });
        });

        // Print single payment details
        $(document).on('click', '#print_payment', function (e) {
           e.preventDefault(); 

            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,                   
                importCSS: true,                
                importStyle: true,          
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",                      
                removeInline: false, 
                printDelay: 500, 
                header: header,  
                footer: footer
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);
            var url = $(this).attr('href');
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#payment_deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            })
        });
            
        //data delete by ajax
        $(document).on('submit', '#payment_deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    $('.data_tbl').DataTable().ajax.reload();
                    toastr.error(data);

                    var filterObj = {
                        branch_id : $('#payments_branch_id').val(),
                        from_date : $('#payments_from_date').val(),
                        to_date : $('#payments_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'payments_', false);

                    filterObj = {
                        branch_id : $('#purchase_branch_id').val(),
                        from_date : $('#purchase_from_date').val(),
                        to_date : $('#purchase_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'purchase_', false);

                    filterObj = {
                        branch_id : $('#order_branch_id').val(),
                        from_date : $('#order_from_date').val(),
                        to_date : $('#order_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'purchase_order_', false);

                    filterObj = {
                        branch_id : $('#ledger_branch_id').val(),
                        from_date : $('#ledger_from_date').val(),
                        to_date : $('#ledger_to_date').val(),
                    };

                    getSupplierAmountsBranchWise(filterObj, 'ledger_', false);
                }
            });
        });

        //Print Ledger
        $(document).on('click', '#print_ledger', function (e) {
            e.preventDefault();

            var url = "{{ route('contacts.supplier.ledger.print', $supplierId) }}";
            var branch_id = $('#ledger_branch_id').val();
            var voucher_type = $('#voucher_type').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: { branch_id, voucher_type, from_date, to_date },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });

        $(document).on('click', '#print_purchase_statements', function (e) {
            e.preventDefault();

            var url = "{{ route('reports.purchases.statement.print') }}";

            var branch_id = $('#purchase_branch_id').val();
            var supplier_id = "{{ $supplier->id }}";
            var from_date = $('.from_purchase_date').val();
            var to_date = $('.to_purchase_date').val();

            $.ajax({
                url : url,
                type : 'get',
                data : {branch_id , supplier_id , from_date, to_date},
                success:function(data){
                    
                    $(data).printThis({
                        debug: false,                   
                        importCSS: true,                
                        importStyle: true,          
                        loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",                      
                        removeInline: false, 
                        printDelay: 500, 
                        formValues: false,   
                    });
                }
            }); 
        });

        //Print Ledger
        $(document).on('click', '#print_payments', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var type = $('#type').val();
            var p_from_date = $('.p_from_date').val();
            var p_to_date = $('.p_to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: { type, p_from_date, p_to_date },
                success: function(data) {

                    $(data).printThis({
                        debug: false,                   
                        importCSS: true,                
                        importStyle: true,          
                        loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",                      
                        removeInline: false,
                        printDelay: 700, 
                        header: null,        
                    });
                }
            }); 
        });

        // Print Packing slip
        $(document).on('click', '#print_supplier_copy', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",
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
            element: document.getElementById('ledger_from_date'),
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
            element: document.getElementById('ledger_to_date'),
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
            element: document.getElementById('payments_from_date'),
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
            element: document.getElementById('payments_to_date'),
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
            element: document.getElementById('purchase_from_date'),
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
            element: document.getElementById('purchase_to_date'),
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
            element: document.getElementById('order_from_date'),
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
            element: document.getElementById('order_to_date'),
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
         function getSupplierAmountsBranchWise(filterObj, showPrefix = 'ledger', is_show_all = true) {

            $.ajax({
               url :"{{ route('contacts.supplier.amounts.branch.wise', $supplier->id) }}",
                type :'get',
                data : filterObj,
                success:function(data){
                    var keys = Object.keys(data);

                    keys.forEach(function (val) {

                        if (is_show_all) {

                            $('.'+val).html(bdFormat(data[val]));
                        }else {

                            $('#'+showPrefix+val).html(bdFormat(data[val]));
                        }
                    });

                    $('#card_total_due').val(data['total_sale_due']);
                }
            });
        }

        getSupplierAmountsBranchWise(filterObj)
    </script>
@endpush
