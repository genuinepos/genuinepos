@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-3.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.css" /> --}}
    <style>
        html {overflow-x: hidden;}
        .title {font-size: 1.8em;}
        .sub-title {text-transform: uppercase;font-size: 1em;color: rgb(216, 204, 204)218, 199, 199);}
        .card-counter {box-shadow: 2px 2px 10px #DADADA;margin: 5px;padding: 20px 10px;background-color: #fff;color: white;
            height: 100px;border-radius: 5px;transition: .3s linear all;}
        .card-counter:hover {box-shadow: 4px 4px 20px #DADADA;transition: .3s linear all;}
        .card-counter.primary {background: #4e54c8;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #8f94fb, #4e54c8);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #8f94fb, #4e54c8);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            color: #FFF;
        }
        .table-responsive { margin-top: -13px;padding-left: 8px;padding-right: 8px;margin-bottom: 5px !important;}

        .card-counter.danger {
            background: #000428;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #004e92, #000428);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #004e92, #000428);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-counter.success {
            background: #ad5389;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #3c1053, #ad5389);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #3c1053, #ad5389);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-counter.blue {
            background: #44A08D;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #093637, #44A08D);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #093637, #44A08D);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-counter.green {
            background: #44A08D;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #093637, #44A08D);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #093637, #44A08D);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-counter.info {
            background: #56CCF2;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #2F80ED, #56CCF2);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #2F80ED, #56CCF2);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-counter .icon {font-size: 3.5em;padding: 10px;opacity: 0.5;color: white !important;}
        .card-counter .count-numbers {position: absolute;right: 35px;top: 20px;font-size: 32px;display: block;}
        .card-counter .count-name {position: absolute;right: 35px;top: 65px;font-style: italic;text-transform: capitalize;opacity: 0.5;display: block;font-size: 18px;}
        .shortcut-icons {font-size: 1.7em;}
    </style>
    <style>
        .select-dropdown,
        .select-dropdown * { margin: 0;padding: 0;position: relative;box-sizing: border-box;}

        .select-dropdown {position: relative;
            /* background-color: #6b082e; */
            border-radius: 4px;
        }

        .select-dropdown select {border-radius: 4px;font-size: 14px !important;font-weight: normal;max-width: 100%;padding: 0px 28px 0px 8px;
            border: none;background-color: #6b082e;-webkit-appearance: none;-moz-appearance: none;appearance: none;color: #fff;
        }

        .select-dropdown select:active,
        .select-dropdown select:focus {outline: none;box-shadow: none;}

        /* Arrow */
        .select-dropdown::after {font-family: "Font Awesome 5 Free";font-weight: 600;content: "\f063";position: absolute;font-size: 10px;top: 0;right: 0;padding: 0px 1em;cursor: pointer;pointer-events: none;-webkit-transition: .25s all ease;-o-transition: .25s all ease;transition: .25s all ease;color: #fff;}
    </style>
    <style>
        .button-group {display: table;}
        .button-group__btn {cursor: pointer;display: table-cell; position: relative;}
        .button-group__btn input[type=radio],
        .button-group__btn input[type=checkbox] {opacity: 0;position: absolute;}
        .button-group__label {background-color: #6b082e;border-bottom: 1px solid #fff;border-right: 1px solid #fff;border-top: 1px solid #fff;color: #fff;display: block;padding: 0 20px;text-align: center;}
        .button-group__btn:first-child .button-group__label {border-left: 1px solid #fff;border-radius: 5px 0 0 5px;}
        .button-group__btn:last-child .button-group__label {border-radius: 0 5px 5px 0;}
        input:checked+.button-group__label {background-color: #ca6d91;border-bottom-color: #fff;border-top-color: #fff;
            color: #fff;}
        .button-group__btn:first-child input:checked+.button-group__label {border-left-color: #fff;}
        .button-group__btn:last-child input:checked+.button-group__label {border-right-color: #fff;}
        .button-group--full-width {table-layout: fixed;width: 100%;}
        .button-group+.button-group {margin-top: 10px;}
        @media only screen and (max-width: 1003px) {
            select { width: 100% !important;}
            .select-dropdown,
            .button-group {display: block !important;}
            .d-flex {display: block !important;}
            .card-counter {display: block !important; height: 165px;}
        }
        .switch_bar {text-align: center; border: 1px solid #ccc5c5;line-height: 20px;border-radius: 5px;background: #fff;
            box-shadow: inset 0 0 5px#ddd;font-size: 25px;margin: 0 4px;margin-bottom: 9px;height: 70px;width: 74px;padding: 4px;}
        .switch_bar i {color: #6b082e;}
        .switch_bar_cards {align-items: center;justify-content: center;}
        .table-title {color: #6b082e;}
        .card {border: 1px solid #6b082e;}
    </style>
@endpush
@section('title', 'Dashboard - ')
@section('content')
    @if (auth()->user()->permission->dashboard['dash_data'] == '1')
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content">
                    <div class="row mx-3 mt-3 switch_bar_cards">

                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-line"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-group"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-receipt"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-home"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-file-invoice"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-pie"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-line"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-group"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-receipt"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-home"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-file-invoice"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-pie"></i></span>
                                <p></p>
                            </a>
                        </div>

                    </div>
                    <div class="">
                        <div class="row mx-2 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <input type="hidden" id="date_range" value="{{ $thisMonth }}">
                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                    <div class="select-dropdown">
                                        <select name="branch_id" id="branch_id">
                                            <option value="">All Branch</option>
                                            <option value="NULL">
                                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                (Head Office)</option>
                                            @foreach ($branches as $br)
                                                <option value="{{ $br->id }}">
                                                    {{ $br->name . '/' . $br->branch_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                @endif

                                <div class="button-group">
                                    <label class="button-group__btn" id="date" data-value="{{ $toDay }}">
                                        <input type="radio" name="group" />
                                        <span class="button-group__label">Current Day</span>
                                    </label>

                                    <label class="button-group__btn">
                                        <input type="radio" name="group" id="date" data-value="{{ $thisWeek }}" />
                                        <span class="button-group__label">This Week</span>
                                    </label>

                                    <label class="button-group__btn" id="date" data-value="{{ $thisMonth }}">
                                        <input type="radio" checked name="group" />
                                        <span class="button-group__label">This Month</span>
                                    </label>

                                    <label class="button-group__btn" id="date" data-value="{{ $thisYear }}">
                                        <input type="radio" name="group" />
                                        <span class="button-group__label">This Year</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Cards --}}
                        <div class="mx-3 mt-2">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Purchase</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_purchase"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter success d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-money-check"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Sale</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_sale"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter info d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-clipboard"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Purchase Due</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_purchase_due"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter danger d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Invoice Due</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_sale_due"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card-counter info d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Expense</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_expense"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter danger d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total User</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_user"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter blue d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Products</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span id="total_product"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter success d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-balance-scale"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title">Total Adjustment</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_adjustment"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-3 m-1">
                <section>
                    <div class="row">
                        <div class="form_element">
                            <div class="section-header">
                                <h6><span class="fas fa-table"></span>Product Stock Alert</h6>
                            </div>
                            <div class="widget_content">
                                <div class="mtr-table">
                                    <div class="table-responsive">
                                        <table id="stock_alert_table" class="display data__table data_tble stock_table"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>S/L</th>
                                                    <th>Product</th>
                                                    <th>Product Code(SKU)</th>
                                                    <th>Current Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <div class="form_element">
                            <div class="section-header">
                                <span class="fas fa-table"></span>
                                <h6>Sales Order</h6>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">
                                    <table id="sales_order_table" class="display data__table data_tble order_table"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice ID</th>
                                                <th>Branch</th>
                                                <th>Customer</th>
                                                <th>Shipment Status</th>
                                                <th>Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="row px-2">
                <div class="col-md-6">
                    <section>
                        <div class="container">
                            <div class="row">
                                <div class="form_element">
                                    <div class="section-header">
                                        <span class="fas fa-table"></span>
                                        <h6>Sales Payment Due</h6>
                                    </div>
                                    <div class="widget_content">
                                        <div class="table-responsive">

                                            <table id="sales_payment_due_table"
                                                class="display data__table data_tble due_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Customer</th>
                                                        <th>Invoice ID</th>
                                                        <th>Branch</th>
                                                        <th>Due Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section>
                        <div class="container">
                            <div class="row">
                                <div class="form_element">
                                    <div class="section-header">
                                        <span class="fas fa-table"></span>
                                        <h6>Purchase Payment Due</h6>
                                    </div>
                                    <div class="widget_content">
                                        <div class="table-responsive">

                                            <table id="purchase_payment_due_table"
                                                class="display data__table data_tble purchase_due_table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Supplier</th>
                                                        <th>P.Invoice ID</th>
                                                        <th>Branch</th>
                                                        <th>Due Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @else
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content">
                    <div class="row mx-3 mt-3">
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-line"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-group"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-receipt"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-home"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-file-invoice"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-pie"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-line"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-group"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-receipt"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-home"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-file-invoice"></i></span>
                                <p></p>
                            </a>
                        </div>
                        <div class="switch_bar">
                            <a href="#" class="bar-link">
                                <span><i class="fas fa-chart-pie"></i></span>
                                <p></p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-primary">Welcome,
                        {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}!</h1>
                </div>
            </div>
        </div>
    @endif

@endsection
@push('scripts')
    @if (auth()->user()->permission->dashboard['dash_data'] == '1')
        <script>
            $(document).on('click', '#date', function() {
                var date_range = $(this).data('value');
                $('#date_range').val(date_range);
                getCardAmount();
                sale_order_table.ajax.reload();
                sale_due_table.ajax.reload();
                purchase_due_table.ajax.reload();
            });

            $(document).on('change', '#branch_id', function() {
                getCardAmount();
                sale_order_table.ajax.reload();
                sale_due_table.ajax.reload();
                purchase_due_table.ajax.reload();
            });

            var table = $('.stock_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                searchable: true,
                ajax: "{{ route('dashboard.stock.alert') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                ],
            });

            var sale_order_table = $('.order_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                aaSorting: [
                    [3, 'asc']
                ],
                "ajax": {
                    "url": "{{ route('dashboard.sale.order') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'shipment_status',
                        name: 'shipment_status'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                ],
            });

            var sale_due_table = $('.due_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                aaSorting: [
                    [3, 'asc']
                ],
                "ajax": {
                    "url": "{{ route('dashboard.sale.due') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                ],
            });

            var purchase_due_table = $('.purchase_due_table').DataTable({
                dom: "Bfrtip",
                buttons: ["excel", "pdf", "print"],
                "processing": true,
                "serverSide": true,
                aaSorting: [
                    [3, 'asc']
                ],
                "ajax": {
                    "url": "{{ route('dashboard.purchase.due') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'sup_name',
                        name: 'sup_name'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                ],
            });

            var __currency = "{{ json_decode($generalSettings->business, true)['currency'] }}";

            function getCardAmount() {
                var date_range = $('#date_range').val();
                var branch_id = $('#branch_id').val();
                $('.card_preloader').show();
                $('.card_amount').html('');
                $.ajax({
                    url: "{{ route('dashboard.card.data') }}",
                    type: 'get',
                    data: {
                        branch_id,
                        date_range
                    },
                    success: function(data) {
                        $('.card_preloader').hide();
                        $('#total_purchase').html(__currency + ' ' + parseFloat(data.totalPurchase).toFixed(2));
                        $('#total_sale').html(__currency + ' ' + parseFloat(data.total_sale).toFixed(2));
                        $('#total_purchase_due').html(__currency + ' ' + parseFloat(data.totalPurchaseDue)
                            .toFixed(2));
                        $('#total_sale_due').html(__currency + ' ' + parseFloat(data.totalSaleDue).toFixed(2));
                        $('#total_expense').html(__currency + ' ' + parseFloat(data.totalExpense).toFixed(2));
                        $('#total_user').html(data.users);
                        $('#total_product').html(data.products);
                        $('#total_adjustment').html(__currency + ' ' + parseFloat(data.total_adjustment)
                            .toFixed(2));
                    }
                });
            }
            getCardAmount();

        </script>
    @endif
@endpush
{{-- <script>
            // $(document).ready(function() {
                // $('#stock_alert_table').DataTable({
                //     dom: "Bfrtip",
                //     buttons: ["excel", "pdf", "print"],
                //     pageLength: 4,
                // });

                // $('#sales_order_table').DataTable({
                //     dom: "Bfrtip",
                //     buttons: ["excel", "pdf", "print"],
                //     pageLength: 4,
                // });

                // $('#sales_payment_due_table').DataTable({
                //     dom: "Bfrtip",
                //     buttons: ["excel", "pdf", "print"],
                //     pageLength: 4,
                // });

                // $('#purchase_payment_due_table').DataTable({
                //     dom: "Bfrtip",
                //     buttons: ["excel", "pdf", "print"],
                //     pageLength: 4,
                // });
            // });
        Highcharts.chart('chart1', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'Historic and Estimated Worldwide Population Growth by Region'
            },
            subtitle: {
                text: 'Source: Wikipedia.org'
            },
            xAxis: {
                categories: ['1750', '1800', '1850', '1900', '1950', '1999', '2050'],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: 'Billions'
                },
                labels: {
                    formatter: function() {
                        return this.value / 1000;
                    }
                }
            },
            tooltip: {
                split: true,
                valueSuffix: ' millions'
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: [{
                name: 'Asia',
                data: [502, 635, 809, 947, 1402, 3634, 5268]
            }, {
                name: 'Africa',
                data: [106, 107, 111, 133, 221, 767, 1766]
            }, {
                name: 'Europe',
                data: [163, 203, 276, 408, 547, 729, 628]
            }, {
                name: 'America',
                data: [18, 31, 54, 156, 339, 818, 1201]
            }, {
                name: 'Oceania',
                data: [2, 2, 2, 6, 13, 30, 46]
            }]
        });

    </script> --}}
