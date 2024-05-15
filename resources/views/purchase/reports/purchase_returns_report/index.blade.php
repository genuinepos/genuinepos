@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Purchase Return Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Purchase Return Report') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Supplier') }}</strong></label>
                                                    <select name="supplier_account_id" class="form-control select2" id="supplier_account_id" autofocus>
                                                        <option data-supplier_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option data-supplier_name="{{ $supplierAccount->name . '/' . $supplierAccount->phone }}" value="{{ $supplierAccount->id }}">{{ $supplierAccount->name . '/' . $supplierAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
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

                        <div class="card">
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Voucher No') }}</th>
                                                <th>{{ __('Parent Purchase') }}</th>
                                                <th>{{ __('Shop/Business') }}</th>
                                                <th>{{ __('Supplier') }}</th>
                                                {{-- <th>{{ __("Created By") }}</th> --}}
                                                <th>{{ __('Total Item') }}</th>
                                                <th>{{ __('Total Qty') }}</th>
                                                <th>{{ __('Net total Amt') }}.</th>
                                                <th>{{ __('Return Discount') }}</th>
                                                <th>{{ __('Return Tax') }}</th>
                                                <th>{{ __('Total Returned Amount') }}</th>
                                                <th>{{ __('Received') }}</th>
                                                <th>{{ __('Due') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="5" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                                <th id="total_item" class="text-white"></th>
                                                <th id="total_qty" class="text-white"></th>
                                                <th id="net_total_amount" class="text-white"></th>
                                                <th id="return_discount_amount" class="text-white"></th>
                                                <th id="return_tax_amount" class="text-white"></th>
                                                <th id="total_return_amount" class="text-white"></th>
                                                <th id="received_amount" class="text-white"></th>
                                                <th id="due" class="text-white"></th>
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
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.purchase.returns.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'parent_invoice_id',
                    name: 'parent_invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'supplier_name',
                    name: 'suppliers.name'
                },
                // {data: 'created_by', name: 'users.name'},
                {
                    data: 'total_item',
                    name: 'total_item',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'net_total_amount',
                    name: 'net_total_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'return_discount_amount',
                    name: 'return_discount_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'return_tax_amount',
                    name: 'return_tax_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_return_amount',
                    name: 'total_return_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'received_amount',
                    name: 'received_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'due',
                    name: 'due',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));

                var return_discount_amount = sum_table_col($('.data_tbl'), 'return_discount_amount');
                $('#return_discount_amount').text(bdFormat(return_discount_amount));

                var return_tax_amount = sum_table_col($('.data_tbl'), 'return_tax_amount');
                $('#return_tax_amount').text(bdFormat(return_tax_amount));

                var total_return_amount = sum_table_col($('.data_tbl'), 'total_return_amount');
                $('#total_return_amount').text(bdFormat(total_return_amount));

                var received_amount = sum_table_col($('.data_tbl'), 'received_amount');
                $('#received_amount').text(bdFormat(received_amount));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));

                $('.data_preloader').hide();
            }
        });

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

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.purchase.returns.print') }}";

            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var supplier_account_id = $('#supplier_account_id').val();
            var supplier_name = $('#supplier_account_id').find('option:selected').data('supplier_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    supplier_account_id,
                    supplier_name,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                    });
                }
            });
        });

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

        // Make print
        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
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
            format: 'DD-MM-YYYY'
        });
    </script>
@endpush
