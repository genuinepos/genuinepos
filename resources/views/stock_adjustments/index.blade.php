@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Stock Adjustment List- ')
@section('content')
    <div class="body-woaper">
        <div class="border-class">
            <div class="main__content">
                <div class="sec-name">
                    <div class="name-head">
                        <h5>{{ __('Stock Adjustments') }}</h5>
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
                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                            <div class="col-md-2">
                                                <label><strong>{{ __('Shop/Business') }}</strong></label>
                                                <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                    <option value="">{{ __('All') }}</option>
                                                    <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">
                                                            @php
                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                $branchCode = '-' . $branch->branch_code;
                                                            @endphp
                                                            {{ $branchName . $areaName . $branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-md-2">
                                            <label><strong>{{ __('Type') }}</strong></label>
                                            <select name="type" id="type" class="form-control" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                @foreach (\App\Enums\StockAdjustmentType::cases() as $type)
                                                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>{{ __('From Date') }}</strong></label>
                                            <div class="input-group">
                                                <input name="from_date" class="form-control" id="from_date" value="{{ $generalSettings['business_or_shop__financial_year_start_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>{{ __('To Date') }}</strong></label>
                                            <div class="input-group">
                                                <input name="to_date" class="form-control" id="to_date" value="{{ $generalSettings['business_or_shop__financial_year_end_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
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
                            <h6>{{ __('List of Stock Adjustments') }}</h6>
                        </div>

                        <div class="col-6 d-flex justify-content-end">
                            @if (auth()->user()->can('stock_adjustment_add'))
                                <a href="{{ route('stock.adjustments.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="widget_content">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>
                        <div class="table-responsive" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ __('Action') }}</th>
                                        <th class="text-start">{{ __('Date') }}</th>
                                        <th class="text-start">{{ __('Voucher No') }}</th>
                                        <th class="text-start">{{ __('Shop/Business') }}</th>
                                        <th class="text-start">{{ __('Ledger Account') }}</th>
                                        <th class="text-start">{{ __('Reason') }}</th>
                                        <th class="text-start">{{ __('Type') }}</th>
                                        <th class="text-start">{{ __('Total Amount') }}</th>
                                        <th class="text-start">{{ __('Received Amount') }}</th>
                                        <th class="text-start">{{ __('Created By') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="7" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                        <th id="net_total_amount" class="text-white text-end"></th>
                                        <th id="recovered_amount" class="text-white text-end"></th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var stockAdjustmentsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('stock.adjustments.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.type = $('#type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'stock_adjustments.date'
                },
                {
                    data: 'voucher_no',
                    name: 'stock_adjustments.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'expense_ledger',
                    name: 'accounts.name'
                },
                {
                    data: 'reason',
                    name: 'stock_adjustments.reason'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'net_total_amount',
                    name: 'stock_adjustments.net_total_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'recovered_amount',
                    name: 'stock_adjustments.recovered_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'created_by',
                    name: 'users.name'
                },

            ],
            fnDrawCallback: function() {
                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));
                var recovered_amount = sum_table_col($('.data_tbl'), 'recovered_amount');
                $('#recovered_amount').text(bdFormat(recovered_amount));
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
            stockAdjustmentsTable.ajax.reload();
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
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'content': "{{ __('Are you sure?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    stockAdjustmentsTable.ajax.reload(null, false);
                    toastr.error(data);
                }
            });
        });
    </script>

    <script>
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
        })

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
        })
    </script>
@endpush
