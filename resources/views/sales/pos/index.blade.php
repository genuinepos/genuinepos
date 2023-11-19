@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Pos Sales List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>{{ __("Manage Add Sales") }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-3">
                                                        <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            <option value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                        $branchCode = '-' . $branch->branch_code;
                                                                    @endphp
                                                                    {{  $branchName.$areaName.$branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Payment Status") }}</strong></label>
                                                    <select name="payment_status" id="payment_status" class="form-control">
                                                        <option value="">{{ __("All") }}</option>
                                                        @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                            <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Customer") }}</strong></label>
                                                    <select name="customer_account_id" class="form-control select2" id="customer_account_id" autofocus>
                                                        <option value="">{{ __("All") }}</option>
                                                        @foreach ($customerAccounts as $customerAccount)
                                                            <option data-customer_account_name="{{ $customerAccount->name.'/'.$customerAccount->phone }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name.'/'.$customerAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("From Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control"  autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("To Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
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
                                <div class="col-6">
                                    <h6>{{ __('List Of Pos Sales') }}</h6>
                                </div>

                                @if(auth()->user()->can('create_add_sale'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('sales.pos.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table id="sales-table" class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Action") }}</th>
                                                <th>{{ __("Date") }}</th>
                                                <th>{{ __("Invoice ID") }}</th>
                                                <th>{{ __("Shop") }}</th>
                                                <th>{{ __("Customer") }}</th>
                                                <th>{{ __("Payment Status") }}</th>
                                                <th>{{ __("Total Item") }}</th>
                                                <th>{{ __("Total Qty") }}</th>
                                                <th>{{ __("Total Invoice Amt") }}</th>
                                                <th>{{ __("Received Amount") }}</th>
                                                <th>{{ __('Return') }}</th>
                                                <th>{{ __("Due") }}</th>
                                                <th>{{ __("Created By") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="6" class="text-white text-end">{{ __("Total") }} : ({{ $generalSettings['business__currency'] }})</th>
                                                <th id="total_item" class="text-white text-end"></th>
                                                <th id="total_qty" class="text-white text-end"></th>
                                                <th id="total_invoice_amount" class="text-white text-end"></th>
                                                <th id="received_amount" class="text-white text-end"></th>
                                                <th id="sale_return_amount" class="text-white text-end"></th>
                                                <th id="due" class="text-white text-end"></th>
                                                <th class="text-white text-end">---</th>
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

    <div class="modal fade" id="editShipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var salesTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('sales.pos.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_account_id = $('#customer_account_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'sales.invoice_id', className: 'fw-bold'},
                {data: 'branch', name: 'branches.name'},
                {data: 'customer_name', name: 'customers.name'},
                {data: 'payment_status', name: 'created_by.name', className: 'text-start'},
                {data: 'total_item', name: 'total_item', className: 'text-end fw-bold'},
                {data: 'total_qty', name: 'total_qty', className: 'text-end fw-bold'},
                {data: 'total_invoice_amount', name: 'total_invoice_amount', className: 'text-end fw-bold'},
                {data: 'received_amount', name: 'paid', className: 'text-end fw-bold'},
                {data: 'sale_return_amount', name: 'sale_return_amount', className: 'text-end fw-bold'},
                {data: 'due', name: 'due', className: 'text-end fw-bold'},
                {data: 'created_by', name: 'created_by.name', className: 'text-end fw-bold'},

            ],fnDrawCallback: function() {
                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var total_invoice_amount = sum_table_col($('.data_tbl'), 'total_invoice_amount');
                $('#total_invoice_amount').text(bdFormat(total_invoice_amount));

                var received_amount = sum_table_col($('.data_tbl'), 'received_amount');
                $('#received_amount').text(bdFormat(received_amount));

                var sale_return_amount = sum_table_col($('.data_tbl'), 'sale_return_amount');
                $('#sale_return_amount').text(bdFormat(sale_return_amount));

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

        $(document).on('click', '#editShipmentDetails', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#editShipmentDetailsModal').html(data);
                    $('#editShipmentDetailsModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#shipment_shipment_address').focus().select();
                    }, 500);
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

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
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

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
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        // Print Packing slip
        $(document).on('click', '#PrintChallanBtn', function (e) {
            e.preventDefault();
            $('.data_preloader').show();

            var url = $(this).attr('href');

            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // Print Packing slip
        $(document).on('click', '#printPackingSlipBtn', function (e) {
            e.preventDefault();
            $('.data_preloader').show();

            var url = $(this).attr('href');

            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    salesTable.ajax.reload();
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
