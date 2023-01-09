@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Purchase Payment Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="far fa-money-bill-alt"></span>
                                <h5>@lang('menu.purchase_payment_report')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                            </a>
                        </div>

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form_element rounded mt-0 mb-3">
                                        <div class="element-body">
                                            <form id="filter_form">
                                                <div class="form-group row">
                                                    @if ($generalSettings['addons__branches'] == 1)
                                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                            <div class="col-md-2">
                                                                <label><strong>@lang('menu.business_location') :</strong></label>
                                                                <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                                    <option value="">@lang('menu.all')</option>
                                                                    <option value="NULL">{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</option>
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
                                                        <label><strong>@lang('menu.supplier') : </strong></label>
                                                        <select name="supplier_id" class="form-control submit_able select2" id="supplier_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            @foreach ($suppliers as $supplier)
                                                                <option value="{{ $supplier->id }}">{{$supplier->name.' ('.$supplier->phone.')'}} </option>
                                                            @endforeach
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
                                                                    <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="print_report"><i class="fas fa-print "></i>@lang('menu.print')</a>
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
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.voucher_no')</th>
                                                <th>@lang('menu.supplier')</th>
                                                <th>@lang('menu.payment_method')</th>
                                                <th>@lang('menu.purchase_invoice_id')</th>
                                                <th>@lang('menu.amount')({{$generalSettings['business__currency']}})</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="5" class="text-end text-white">@lang('menu.total') : </th>
                                                <th class="text-white"><span id="paid_amount"></span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
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
            { extend: 'excel',text: 'Excel',className: 'btn btn-primary' },
            { extend: 'pdf',text: 'Pdf',className: 'btn btn-primary' },
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.purchase.payments.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.supplier_id = $('#supplier_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'payment_invoice', name: 'invoice_id'},
            {data: 'supplier_name', name: 'suppliers.name'},
            {data: 'pay_mode', name: 'pay_mode'},
            {data: 'purchase_invoice', name: 'purchases.invoice_id'},
            {data: 'paid_amount', name: 'paid_amount', className: 'text-end'},
        ],
        fnDrawCallback: function() {
            var paid_amount = sum_table_col($('.data_tbl'), 'paid_amount');
            $('#paid_amount').text(bdFormat(paid_amount));
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

     //Print purchase Payment report
     $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.purchase.payments.print') }}";
        var branch_id = $('#branch_id').val();
        var supplier_id = $('#supplier_id').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, supplier_id, from_date, to_date},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
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
