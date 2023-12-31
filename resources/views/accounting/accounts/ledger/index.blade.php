@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .widget_content p {
            padding: 0px 0px;
        }
    </style>
@endpush
@section('title', 'Account Ledger - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Account Ledger') }} - <strong>{{ $account->name }}</strong></h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <table class="display table modal-table table-sm m-0">
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" class="text-center">{{ __('Account Summary') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-end"></th>
                                                    <th class="text-end">{{ __('Debit') }}</th>
                                                    <th class="text-end">{{ __('Credit') }}</th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Opening Balance') }} :</th>
                                                    <th class="text-end" id="debit_opening_balance"></th>
                                                    <th class="text-end" id="credit_opening_balance"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Current Total') }} :</th>
                                                    <th class="text-end" id="total_debit"></th>
                                                    <th class="text-end" id="total_credit"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Closing Balance') }} :</th>
                                                    <th class="text-end" id="debit_closing_balance"></th>
                                                    <th class="text-end" id="credit_closing_balance"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element rounded mt-0">
                                    <div class="element-body">
                                        <form id="filter_account_ledgers" method="get">
                                            <div class="form-group row g-2 align-items-end">
                                                @if ($account?->group?->is_global == 1)
                                                    @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && !auth()->user()->branch_id)
                                                        <div class="col-md-3">
                                                            <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                                <option value="">{{ __('All') }}</option>
                                                                <option data-branch_name="{{ $generalSettings['business__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
                                                                @foreach ($branches as $branch)
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                        $branchCode = '-' . $branch->branch_code;
                                                                    @endphp
                                                                    <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                        {{ $branchName . $areaName . $branchCode }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('From Date') }} :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('To Date') }} :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Note/Remarks') }} :</strong></label>
                                                    <select name="note" class="form-control" id="note">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option selected value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Voucher Details') }} :</strong></label>
                                                    <select name="voucher_details" class="form-control" id="voucher_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Transaction Details') }} :</strong></label>
                                                    <select name="transaction_details" class="form-control" id="transaction_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Inventory List') }} :</strong></label>
                                                    <select name="inventory_list" class="form-control" id="inventory_list">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                                    <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="print_report"><i class="fas fa-print "></i>{{ __('Print') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-1">
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                                </div>

                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">{{ __('Date') }}</th>
                                                <th class="text-start">{{ __('Particulars') }}</th>
                                                <th class="text-start">{{ __('Voucher Type') }}</th>
                                                <th class="text-start">{{ __('Voucher No') }}</th>
                                                <th class="text-start">{{ __('Debit') }}</th>
                                                <th class="text-start">{{ __('Credit') }}</th>
                                                <th class="text-start">{{ __('Running Balance') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-white" style="text-align: right!important;"> {{ __('Current Total') }} : </th>
                                                <th id="table_total_debit" class="text-white"></th>
                                                <th id="table_total_credit" class="text-white"></th>
                                                <th id="table_current_balance" class="text-white"></th>
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
            </div>
        </div>
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var accountLedgerTable = $('.data_tbl').DataTable({
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
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('accounts.ledger.index', [$account->id]) }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.branch_name = $('#branch_id').find('option:selected').data('branch_name');
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.note = $('#note').val();
                    d.transaction_details = $('#transaction_details').val();
                    d.voucher_details = $('#voucher_details').val();
                    d.inventory_list = $('#inventory_list').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'account_ledgers.date'
                },
                {
                    data: 'particulars',
                    name: 'particulars'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'debit',
                    name: 'account_ledgers.debit',
                    className: 'text-end'
                },
                {
                    data: 'credit',
                    name: 'account_ledgers.credit',
                    className: 'text-end'
                },
                {
                    data: 'running_balance',
                    name: 'account_ledgers.running_balance',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        // accountLedgerTable.buttons().container().appendTo('#exportButtonsContainer');

        // Submit filter form by select input changing
        $(document).on('submit', '#filter_account_ledgers', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            accountLedgerTable.ajax.reload(null, false);

            getAccountClosingBalance();
        });

        //Print account ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('accounts.ledger.print', [$account->id]) }}";

            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var note = $('#note').val();
            var transaction_details = $('#transaction_details').val();
            var voucher_details = $('#voucher_details').val();
            var inventory_list = $('#inventory_list').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    from_date,
                    to_date,
                    note,
                    transaction_details,
                    voucher_details,
                    inventory_list
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
                        // footer: 'Footer Text',
                    });
                }
            });
        });

        function getAccountClosingBalance() {

            var branch_id = $('#branch_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var filterObj = {
                branch_id: branch_id ? branch_id : null,
                from_date: from_date ? from_date : null,
                to_date: to_date ? to_date : null,
            };

            var url = "{{ route('accounts.balance', $account->id) }}";

            $.ajax({
                url: url,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#debit_opening_balance').html('');
                    $('#credit_opening_balance').html('');
                    $('#debit_closing_balance').html('');
                    $('#credit_closing_balance').html('');

                    $('#table_total_debit').html(data.all_total_debit > 0 ? bdFormat(data.all_total_debit) : '');
                    $('#table_total_credit').html(data.all_total_credit ? bdFormat(data.all_total_credit) : '');
                    $('#table_current_balance').html(data.closing_balance > 0 ? data.closing_balance_string : '');

                    if (data.opening_balance_side == 'dr') {

                        $('#debit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    } else {

                        $('#credit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                    }

                    $('#total_debit').html(data.curr_total_debit > 0 ? bdFormat(data.curr_total_debit) : '');
                    $('#total_credit').html(data.curr_total_credit > 0 ? bdFormat(data.curr_total_credit) : '');

                    if (data.closing_balance_side == 'dr') {

                        $('#debit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                    } else {

                        $('#credit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                    }
                }
            });
        }

        getAccountClosingBalance();

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

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

        // Print Packing slip
        $(document).on('click', '#PrintChallanBtn', function(e) {
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
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // Print Packing slip
        $(document).on('click', '#printPackingSlipBtn', function(e) {
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
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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
            element: document.getElementById('to_date'),
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
@endpush
