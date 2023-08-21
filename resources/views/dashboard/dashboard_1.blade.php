@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>
    <link href="{{ asset('backend/asset/css/dashboard.css') }}" rel="stylesheet" type="text/css">
    <style>
        .widget_content .table-responsive {
            min-height: 40vh !important;
        }
    </style>
@endpush
@section('title', 'Dashboard - ')
@section('content')
    @if (auth()->user()->can('dash_data'))
        <div id="dashboard" class="p-3">
            <div class="row mb-3">
                <div class="main__content">
                    <div class="welcome-user">
                        <div class="alert mb-1 py-0 w-100 h-auto alert-success">
                            <span>@lang('menu.welcome') <strong>{{ auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</strong></span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap mt-2 switch_bar_cards">

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
                                <span><i class="fas fa-plus-square text-white"></i></span>
                            </a>
                            <p>@lang('menu.add_shortcut')</p>
                        </div>
                    </div>

                    <div class="">
                        <div class="row mt-3">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <input type="hidden" id="date_range" value="{{ $thisMonth }}">
                                @if ($generalSettings['addons__branches'] == 1)
                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="select-dropdown">
                                            <select name="branch_id" id="branch_id">
                                                <option value="">@lang('menu.all_business_locations')</option>
                                                <option value="NULL">
                                                    {{ $generalSettings['business__shop_name'] }}
                                                    (@lang('menu.head_office'))</option>
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
                                {{-- <div class="button-group">
                                    <label class="button-group__btn" id="date" data-value="{{ $toDay }}">
                                        <input type="radio" name="group" />
                                        <span class="button-group__label">@lang('menu.current_day')</span>
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
                                </div> --}}
                                <div class="select-dropdown">
                                    <select name="date" id="date">
                                        <option value="{{ $toDay }}">@lang('menu.current_day')</option>
                                        <option value="{{ $thisWeek }}">@lang('menu.this_week')</option>
                                        <option value="{{ $thisMonth }}">@lang('menu.this_month')</option>
                                        <option value="{{ $thisYear }}">@lang('menu.this_year')</option>
                                        <option value="all_time">@lang('menu.all_time')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Cards --}}
                        <div class="mt-3">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title text-white">@lang('menu.total_purchase')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.total_sale')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.purchase_due')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.invoice_due')</h3>
                                            <h1 class="title">
                                                <i class="fas fa-sync fa-spin card_preloader"></i>
                                                <span class="card_amount" id="total_sale_due"></span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card-counter info d-flex justify-content-around align-content-center">
                                        <div class="icon">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <div class="numbers px-1">
                                            <h3 class="sub-title text-white">@lang('menu.expense')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.total_user')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.total_products')</h3>
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
                                            <h3 class="sub-title text-white">@lang('menu.total_adjustment')</h3>
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

            <div class="row g-3">
                <div class="col-md-6">
                    <section>
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6>
                                    <span class="fas fa-table"></span>@lang('menu.stock_alert_of')
                                    <b>
                                        @if (auth()->user()->branch_id)
                                            {{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}
                                        @else
                                            {{ $generalSettings['business__shop_name'] }}
                                        @endif
                                    </b>
                                </h6>
                                <a href="#">@lang('menu.see_more')</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">
                                    <table id="stock_alert_table" class="display data__table data_tble stock_table"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.sl')</th>
                                                <th>@lang('menu.product')</th>
                                                <th>@lang('menu.product_code')(SKU)</th>
                                                <th>@lang('menu.current_stock')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section>
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span> @lang('menu.sales_order')</h6>
                                <a href="#">@lang('menu.see_more')</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">
                                    <table id="sales_order_table" class="display data__table data_tble order_table"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.invoice_id')</th>
                                                <th>@lang('menu.branch')</th>
                                                <th>@lang('menu.customer')</th>
                                                <th>@lang('menu.shipment_status')</th>
                                                <th>@lang('menu.created_by')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section>
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span>@lang('menu.sales_payment_due')</h6>
                                <a href="#">@lang('menu.see_more')</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">

                                    <table id="sales_payment_due_table"
                                        class="display data__table data_tble due_table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.customer')</th>
                                                <th>@lang('menu.invoice_id')</th>
                                                <th>@lang('menu.branch')</th>
                                                <th>@lang('menu.due_amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section>
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span>@lang('menu.purchase_payment_due')</h6>
                                <a href="#">@lang('menu.see_more')</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">

                                    <table id="purchase_payment_due_table"
                                        class="display data__table data_tble purchase_due_table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.supplier')</th>
                                                <th>{{ __('P.Invoice ID') }}</th>
                                                <th>@lang('menu.branch')</th>
                                                <th>@lang('menu.due_amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!--Add shortcut menu modal-->
        <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
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
                    <h1 class="text-primary display-5">@lang('menu.welcome'),
                        <strong>{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}!</strong>
                    </h1>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    @if (auth()->user()->can('dash_data'))
        <script>
            $(document).on('change', '#date', function() {
                var date_range = $(this).val();
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
                "ajax": {
                    "url": "{{ route('dashboard.stock.alert') }}",
                    "data": function(d) {d.branch_id = $('#branch_id').val()}
                },
                // ajax: "{{ route('dashboard.stock.alert') }}",
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

            var __currency = "{{ $generalSettings['business__currency'] }}";

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
                        $('#total_purchase').html(__currency + ' ' + data.totalPurchase);
                        $('#total_sale').html(__currency + ' ' + data.total_sale);
                        $('#total_purchase_due').html(__currency + ' ' + data.totalPurchaseDue);
                        $('#total_sale_due').html(__currency + ' ' + data.totalSaleDue);
                        $('#total_expense').html(__currency + ' ' + data.totalExpense);
                        $('#total_user').html(data.users);
                        $('#total_product').html(data.products);
                        $('#total_adjustment').html(__currency + ' ' + data.total_adjustment);
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
