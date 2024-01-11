@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Category Wise Expense Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('menu.category_wise_expense_report') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <form id="filter_form">

                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.business_location') </strong></label>
                                        <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">
                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif


                                <div class="col-md-2">
                                    <label><strong>@lang('menu.category') </strong></label>
                                    <select name="category_id" class="form-control submit_able select2" id="category_id">
                                        <option value="">@lang('menu.all')</option>
                                        @foreach ($expenseCategories as $expenseCategory)
                                            <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name . ' (' . $expenseCategory->code . ')' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label><strong>@lang('menu.from_date') </strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                        </div>
                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label><strong>@lang('menu.to_date') </strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                        </div>
                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
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
            </div>
        </div>
        <div class="card">
            <div class="section-header">
                <div class="col-md-10">
                    <h6>{{ __('menu.category_wise_expense_report') }}</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr>
                                <th class="text-start">@lang('menu.date')</th>
                                <th class="text-start">@lang('menu.expense_category')</th>
                                <th class="text-start">@lang('menu.reference_id')</th>
                                <th class="text-start">@lang('menu.b_location')</th>
                                <th class="text-start">{{ __('Expanse For') }}</th>
                                <th class="text-start">@lang('menu.amount')({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="4" class="text-end text-white">@lang('menu.total') </th>
                                <th></th>
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
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('expanses.category.wise.expense') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.admin_id = $('#admin_id').val();
                    d.category_id = $('#category_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'expanses.date'
                },
                {
                    data: 'category_name',
                    name: 'expanse_categories.name'
                },
                {
                    data: 'invoice_id',
                    name: 'expanses.invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'from',
                    name: 'branches.name'
                },
                {
                    data: 'user_name',
                    name: 'users.name'
                },
                {
                    data: 'amount',
                    name: 'expense_descriptions.amount',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {
                var amount = sum_table_col($('.data_tbl'), 'amount');
                $('#total_amount').text(bdFormat(amount));
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
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.expenses.category.wise.print') }}";
            var branch_id = $('#branch_id').val();
            var admin_id = $('#admin_id').val();
            var category_id = $('#category_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    admin_id,
                    category_id,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
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
