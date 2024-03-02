@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Payroll Payments Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Payroll Payments Report") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end">
                                    {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Shop/Business') }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option data-branch_name="All" value="">{{ __("All") }}</option>
                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'].'('.__('Business').')' }}" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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
                                        <label><strong>{{ __("From Date") }} : </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __("To Date") }} : </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                        <a href="{{ route('reports.payroll.payments.print') }}" class="btn btn-sm btn-primary float-end " id="printReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Date") }}</th>
                                    <th>{{ __("Payment Voucher") }}</th>
                                    <th>{{ __("Shop/Business") }}</th>
                                    <th>{{ __('Against Payroll') }}</th>
                                    <th>{{ __("Expense Ledger A/c") }}</th>
                                    <th>{{ __("Remarks") }}</th>
                                    <th>{{ __("Paid To") }}</th>
                                    <th>{{ __("Paid From") }}</th>
                                    <th>{{ __("Type/Method") }}</th>
                                    <th>{{ __("Trans No") }}</th>
                                    <th>{{ __("Cheque No") }}</th>
                                    <th>{{ __("Paid Amount") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="11" class="text-end text-white">{{ __("Total") }} : </th>
                                    <th class="text-white" id="total_amount"></th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel', text: "{{ __('Excel') }}", className: 'btn btn-primary'},
            {extend: 'pdf', text: "{{ __('Pdf') }}", className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        "searching" : true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.payroll.payments.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        }, columns: [
            {data: 'date', name: 'accountingVoucher.date'},
            {data: 'voucher_no', name: 'accountingVoucher.voucher_no', className: 'fw-bold'},
            {data: 'branch', name: 'accountingVoucher.branch.name'},
            {data: 'reference', name: 'accountingVoucher.payrollRef.voucher_no'},
            {data: 'expense_account', name: 'account.name'},
            {data: 'remarks', name: 'accountingVoucher.remarks'},
            {data: 'paid_to', name: 'accountingVoucher.payrollRef.user.name'},
            {data: 'paid_from', name: 'accountingVoucher.voucherCreditDescription.account.name'},
            {data: 'payment_method', name: 'accountingVoucher.voucherCreditDescription.paymentMethod.name'},
            {data: 'transaction_no', name: 'accountingVoucher.voucherCreditDescription.transaction_no'},
            {data: 'cheque_no', name: 'accountingVoucher.voucherCreditDescription.cheque_no'},
            {data: 'total_amount',name: 'accountingVoucher.voucherCreditDescription.cheque_serial_no', className: 'text-end fw-bold'},
        ], fnDrawCallback: function() {

            var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
            $('#total_amount').text(parseFloat(total_amount).toFixed(2));

            $('.data_preloader').hide();
        },
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

    $(document).on('click', '#printReport',function (e) {
        e.preventDefault();

        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            data: { branch_id, branch_name, from_date, to_date },
            success:function(data){

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header : null,
                    footer : null,
                });

                $('.data_preloader').hide();
            }, error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
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
            }, error: function(err) {

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

        var filename = $(this).attr('filename');
        var body = $('#details .print_modal_details').html();

        document.title = filename;

        setTimeout(function() {
            document.title = "Payroll Report - GPOSS";
        }, 1000);

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
        }, tooltipText: {
            one: 'night',
            other: 'nights'
        }, tooltipNumber: (totalDays) => {
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
