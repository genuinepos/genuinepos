@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Expense Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">

                <div class="name-head">
                    <span class="fas fa-money-bill"></span>
                    <h5>@lang('menu.expense_report')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>

            <div class="p-3">
                <div class="form_element rounded mt-0 mb-3">
                    <div class="element-body">
                        <form id="filter_form">
                            <div class="form-group row">
                                @if ($addons->branches == 1)
                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.business_location') :</strong></label>
                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                    @endif
                                @endif

                                <div class="col-md-2">
                                    <label><strong>@lang('menu.expense_for') :</strong></label>
                                    <select name="admin_id" class="form-control submit_able" id="admin_id" autofocus>
                                        <option value="">@lang('menu.all')</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label><strong>@lang('menu.from_date') :</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="fas fa-calendar-week input_i"></i></span>
                                        </div>
                                        <input type="text" name="from_date" id="datepicker"
                                            class="form-control from_date date"
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label><strong>@lang('menu.to_date') :</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="fas fa-calendar-week input_i"></i></span>
                                        </div>
                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row align-items-end">
                                        <div class="col-6">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i>@lang('menu.print')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.date')</th>
                                    <th class="text-start">@lang('menu.reference_id')</th>
                                    <th class="text-start">@lang('menu.b_location')</th>
                                    <th class="text-start">@lang('menu.expense_for')</th>
                                    <th class="text-start">@lang('menu.payment_status')</th>
                                    <th class="text-start">@lang('menu.tax')</th>
                                    <th class="text-start">@lang('menu.net_total')</th>
                                    <th class="text-start">@lang('menu.paid')</th>
                                    <th class="text-start">@lang('menu.payment_due')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th class="text-start text-white">@lang('menu.total') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <th class="text-start text-white">
                                        <span id="tax_amount"></span>
                                    </th>

                                    <th class="text-start text-white">
                                        <span id="net_total"></span>
                                    </th>

                                    <th class="text-start text-white">
                                        <span id="paid"></span>
                                    </th>

                                    <th class="text-start text-white">
                                        <span id="due"></span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Set accounts in payment and payment edit form

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'}
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.expenses.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.admin_id = $('#admin_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columnDefs: [{
            "targets": [4, 5],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'date', name: 'date' },
            { data: 'invoice_id', name: 'invoice_id'},
            { data: 'from', name: 'branches.name'},
            { data: 'user_name', name: 'users.name' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'tax_percent', name: 'tax_percent' },
            { data: 'net_total', name: 'net_total_amount', className: 'text-end'},
            { data: 'paid', name: 'paid', className: 'text-end'},
            { data: 'due', name: 'due', className: 'text-end'},
        ],
        fnDrawCallback: function() {
            var tax_amount = sum_table_col($('.data_tbl'), 'tax_amount');
            $('#tax_amount').text(parseFloat(tax_amount).toFixed(2));
            var net_total = sum_table_col($('.data_tbl'), 'net_total');
            $('#net_total').text(parseFloat(net_total).toFixed(2));
            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(parseFloat(paid).toFixed(2));
            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(parseFloat(due).toFixed(2));
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
        table.ajax.reload();
        $('.data_preloader').show();
    });

    //Print purchase Payment report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.expenses.print') }}";
        var branch_id = $('#branch_id').val();
        var admin_id = $('#admin_id').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, admin_id, from_date, to_date},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
