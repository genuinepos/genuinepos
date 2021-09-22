@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>
    <link href="{{ asset('public/backend/asset/css/dashboard.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('title', 'Dashboard - ')
@section('content')
    @if (auth()->user()->permission->dashboard['dash_data'] == '1')
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content">
                    <div class="row mx-3 mt-3 switch_bar_cards">
            
                        {{-- <div class="switch_bar">
                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                <a href="{{ route('transfer.stock.to.branch.create') }}" class="bar-link">
                                    <span><i class="fas fa-exchange-alt"></i></span>
                                    <p>Add Transfer</p>
                                </a>
                            @else 
                                <a href="{{ route('transfer.stock.to.warehouse.create') }}" class="bar-link">
                                    <span><i class="fas fa-exchange-alt"></i></span>
                                    <p>Add Transfer</p>
                                </a>
                            @endif
                        </div> --}}

                        <div class="switch_bar">
                            <a href="{{ route('short.menus.modal.form') }}" class="bar-link" id="addShortcutBtn">
                                <span><i class="fas fa-plus-square text-success"></i></span>
                                <p>Add Shortcut</p>
                            </a>
                        </div>
                    </div>

                    <div class="">
                        <div class="row mx-2 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <input type="hidden" id="date_range" value="{{ $thisMonth }}">
                                @if ($addons->branches == 1)
                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="select-dropdown">
                                            <select name="branch_id" id="branch_id">
                                                <option value="">All Business Locations</option>
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

                                    <label class="button-group__btn" id="date" data-value="all_time">
                                        <input type="radio" name="group" />
                                        <span class="button-group__label">All Time</span>
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

        <!--Add shortcut menu modal-->
        <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">Add Shortcut Menus</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="modal-body_shortcuts">
                        <!--begin::Form-->
                    </div>
                </div>
            </div>
        </div>
    @else
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content">
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-primary display-5">Welcome,
                        <strong>{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}!</strong> 
                    </h1>
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
                columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
                    {data: 'name',name: 'name'},
                    {data: 'product_code',name: 'product_code'},
                    {data: 'stock',name: 'stock'},
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
                    "data": function(d) {d.branch_id = $('#branch_id').val();d.date_range = $('#date_range').val();}
                },
                columns: [{data: 'date',name: 'date'},{data: 'invoice_id',name: 'invoice_id'},{data: 'from',name: 'from'},{data: 'customer',name: 'customer'},{data: 'shipment_status',name: 'shipment_status'},{data: 'created_by',name: 'created_by'},
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
                    "data": function(d) {d.branch_id = $('#branch_id').val();d.date_range = $('#date_range').val();}
                },
                columns: [{data: 'customer',name: 'customer'},{data: 'invoice_id',name: 'invoice_id'},{data: 'from',name: 'from'},{data: 'due',name: 'due'},],
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
                    "data": function(d) {d.branch_id = $('#branch_id').val();d.date_range = $('#date_range').val();}
                },
                columns: [{data: 'sup_name',name: 'sup_name'},{data: 'invoice_id',name: 'invoice_id'},{data: 'from',name: 'from'},{data: 'due',name: 'due'},],
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
                    data: {branch_id,date_range},
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
                        $('#total_adjustment').html(__currency + ' ' + parseFloat(data.total_adjustment).toFixed(2));
                    }
                });
            }
            getCardAmount();

            $(document).on('click', '#addShortcutBtn', function (e) {
               e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#modal-body_shortcuts').html(data);
                    $('#shortcutMenuModal').modal('show');
                });
            });

            $(document).on('change', '#check_menu', function () {
                $('#add_shortcut_menu').submit();
            });

            $(document).on('submit', '#add_shortcut_menu', function (e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        allShortcutMenus();
                        //toastr.success(data);
                    }
                });
            });

            // Get all shortcut menus by ajax
            function allShortcutMenus() {
                $.ajax({
                    url: "{{ route('short.menus.show') }}",
                    type: 'get',
                    success: function(data) {
                        $('.switch_bar_cards').html(data);
                    }
                });
            }
            allShortcutMenus();
        </script>
    @endif
@endpush