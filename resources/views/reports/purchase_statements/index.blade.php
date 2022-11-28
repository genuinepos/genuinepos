@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Purchase Statements - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span>
                                <h5>Purchase Statements</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-3">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('menu.supplier') : </strong></label>
                                                    <select name="supplier_id"
                                                        class="form-control submit_able"
                                                        id="supplier_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($suppliers as $sup)
                                                            <option value="{{ $sup->id }}">{{ $sup->name.' ('.$sup->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Purchase Status :</strong></label>
                                                    <select name="status" id="status"
                                                        class="form-control  submit_able">
                                                        <option value="">All</option>
                                                        <option value="1">Purchased</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">Purchased By Order</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start">
                                                                    <i class="fas fa-funnel-dollar"></i> Filter
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end" id="print_purchase_statement_report"><i class="fas fa-print "></i> Print</a>
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
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>Purchase Statement List</h6>
                                </div>
                                @if(auth()->user()->can('purchase_add'))
                                    <div class="col-md-2 d-flex justify-content-end">
                                        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> Add</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.date')</th>
                                                <th>P.Invoice ID</th>
                                                <th>Purchase From</th>
                                                <th>Supplier</th>
                                                <th>Created By</th>
                                                <th>Purchase Status</th>
                                                <th>Total Item</th>
                                                <th>Net Total Amt.</th>
                                                <th>Order Discount</th>
                                                <th>Order Tax</th>
                                                <th>Grand Total</th>
                                                <th>Paid</th>
                                                <th>Return Amount</th>
                                                <th>Due</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="6" class="text-end text-white">Total : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                <th id="total_item" class="text-white"></th>
                                                <th id="net_total_amount" class="text-white"></th>
                                                <th id="order_discount_amount" class="text-white"></th>
                                                <th id="purchase_tax_amount" class="text-white"></th>
                                                <th id="total_purchase_amount" class="text-white"></th>
                                                <th id="paid" class="text-white"></th>
                                                <th id="purchase_return_amount" class="text-white"></th>
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
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('reports.purchases.statement.index') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{"targets": [5],"orderable": false,"searchable": false}],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'created_by',name: 'users.name'},
                {data: 'status',name: 'status'},
                {data: 'total_item',name: 'total_item', className: 'text-end'},
                {data: 'net_total_amount',name: 'net_total_amount', className: 'text-end'},
                {data: 'order_discount_amount',name: 'order_discount_amount', className: 'text-end'},
                {data: 'purchase_tax_amount',name: 'purchase_tax_amount', className: 'text-end'},
                {data: 'total_purchase_amount',name: 'total_purchase_amount', className: 'text-end'},
                {data: 'paid',name: 'paid', className: 'text-end'},
                {data: 'purchase_return_amount',name: 'purchase_return_amount', className: 'text-end'},
                {data: 'due',name: 'due', className: 'text-end'},
            ],fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));

                var order_discount_amount = sum_table_col($('.data_tbl'), 'order_discount_amount');
                $('#order_discount_amount').text(bdFormat(order_discount_amount));

                var purchase_tax_amount = sum_table_col($('.data_tbl'), 'purchase_tax_amount');
                $('#purchase_tax_amount').text(bdFormat(purchase_tax_amount));

                var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
                $('#total_purchase_amount').text(bdFormat(total_purchase_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var purchase_return_amount = sum_table_col($('.data_tbl'), 'purchase_return_amount');
                $('#purchase_return_amount').text(bdFormat(purchase_return_amount));

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
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

         //Print purchase Payment report
         $(document).on('click', '#print_purchase_statement_report', function (e) {
            e.preventDefault();

            var url = "{{ route('reports.purchases.statement.print') }}";

            var branch_id = $('#branch_id').val();
            var supplier_id = $('#supplier_id').val();
            var status = $('#status').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();

            $.ajax({
                url : url,
                type : 'get',
                data : {branch_id, supplier_id, status, from_date, to_date},
                success:function(data){

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
                        formValues: false,
                        canvas: false,
                        beforePrint: null,
                        afterPrint: null
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
            format: 'DD-MM-YYYY'
        });
    </script>
@endpush
