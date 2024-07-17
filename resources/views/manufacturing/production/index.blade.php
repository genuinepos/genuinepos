@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Productions - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Productions') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
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
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-(' . $branch->branch_code . ')';
                                                        @endphp
                                                        {{ $branchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('Status') }}</strong></label>
                                        <div class="input-group">
                                            <select name="status" class="form-control" id="status" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                @foreach (\App\Enums\ProductionStatus::cases() as $productionStatus)
                                                    <option value="{{ $productionStatus->value }}">{{ $productionStatus->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('From Date') }} </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_i"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>{{ __('To Date') }} </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_i"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start">
                                                <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                            </button>
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
                        <h6>{{ __('List of Productions') }}</h6>
                    </div>
                    @if (auth()->user()->can('production_add'))
                        <div class="col-6 d-flex justify-content-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('manufacturing.productions.create') }}"><i class="fas fa-plus-square"></i>@lang('menu.add')</a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div class="table-responsive">
                        <form id="update_product_cost_form" action="">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-black">{{ __('Action') }}</th>
                                        <th class="text-black">{{ __('Date') }}</th>
                                        <th class="text-black">{{ __('Voucher No') }}</th>
                                        <th class="text-black">{{ location_label() }}</th>
                                        <th class="text-black">{{ __('Mfd. Product') }}</th>
                                        <th class="text-black">{{ __('Status') }}</th>
                                        <th class="text-black">{{ __('Per Unit Cost Inc. Tax') }}</th>
                                        <th class="text-black">{{ __('Selling Price Exc. Tax') }}</th>
                                        <th class="text-black">{{ __('Final Qty') }}</th>
                                        <th class="text-black">{{ __('Total Ingredient Cost') }}</th>
                                        <th class="text-black">{{ __('Addl. Production Cost') }}</th>
                                        <th class="text-black">{{ __('Net Cost') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="8" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                        <th id="total_final_output_quantity" class="text-white text-end"></th>
                                        <th id="total_ingredient_cost" class="text-white text-end"></th>
                                        <th id="additional_production_cost" class="text-white text-end"></th>
                                        <th id="net_cost" class="text-white text-end"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>

                @if (auth()->user()->can('production_delete'))
                    <form id="deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div id="details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var production_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>' + "{{ __('Excel') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i>' + "{{ __('Pdf') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i>' + "{{ __('Print') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('manufacturing.productions.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'productions.date'
                },
                {
                    data: 'voucher_no',
                    name: 'productions.voucher_no'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'status',
                    name: 'product_variants.variant_name',
                    className: 'fw-bold'
                },
                {
                    data: 'per_unit_cost_inc_tax',
                    name: 'parentBranch.name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'per_unit_price_exc_tax',
                    name: 'per_unit_price_exc_tax',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_final_output_quantity',
                    name: 'total_final_output_quantity',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_ingredient_cost',
                    name: 'total_ingredient_cost',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'additional_production_cost',
                    name: 'additional_production_cost',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'net_cost',
                    name: 'net_cost',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_final_output_quantity = sum_table_col($('.data_tbl'), 'total_final_output_quantity');
                $('#total_final_output_quantity').text(bdFormat(total_final_output_quantity));

                var total_ingredient_cost = sum_table_col($('.data_tbl'), 'total_ingredient_cost');
                $('#total_ingredient_cost').text(bdFormat(total_ingredient_cost));

                var additional_production_cost = sum_table_col($('.data_tbl'), 'additional_production_cost');
                $('#additional_production_cost').text(bdFormat(additional_production_cost));

                var net_cost = sum_table_col($('.data_tbl'), 'net_cost');
                $('#net_cost').text(bdFormat(net_cost));

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
            production_table.ajax.reload();
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

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
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

                    production_table.ajax.reload(null, false);
                    toastr.error(data);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);
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
