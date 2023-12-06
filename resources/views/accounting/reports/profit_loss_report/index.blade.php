@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .sale_purchase_and_profit_area {
            position: relative;
        }

        .data_preloader {
            top: 2.3%
        }

        .sale_and_purchase_amount_area table tbody tr th {
            text-align: left;
        }

        .sale_and_purchase_amount_area table tbody tr td {
            text-align: left;
        }

        .daily_profit_loss_amount_area table tbody tr td {
            font-size: 12px;
            padding: 3px !important;
        }
    </style>
@endpush
@section('title', 'Profit/Loss - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-money-bill-wave"></span>
                    <h5>{{ __("Profit/Loss") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="p-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <form id="profit_loss_filter_form" method="get">
                                            <div class="form-group row">
                                                @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-3">
                                                        <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                        <select name="branch_id" id="branch_id" class="form-control select2" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business__shop_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __('Business') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option data-branch_name="{{ $branch->name }}" value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label><strong>{{ __('Child Shop') }} </strong></label>
                                                        <select name="branch_id" class="form-control select2" id="child_branch_id">
                                                            <option data-child_branch_name="" value="">{{ __('Select Shop First') }}</option>
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }} </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
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
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="printReport"><i class="fas fa-print "></i> {{ __('Print') }}</a>
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
                    </div>
                </div>

                <div class="sale_purchase_and_profit_area">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div id="data_list">
                        <div class="daily_profit_loss_amount_area">
                            <div class="row g-3">
                                <div class="col-md-12 col-sm-12 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="display table modal-table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-end">
                                                            <strong>{{ __('Total Sale') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                                        </td>

                                                        <td class="text-end text-success">
                                                            0.00
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Individual Sold Product Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Sale Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end">
                                                            <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                                        </td>

                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>

                                                        <td class="text-end text-success fw-bold">
                                                            0.00
                                                        </td>
                                                    </tr>



                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Stock Adjustment') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-success">
                                                            0.00
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Expense') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Sales Return') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Payroll') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                                        <td class="text-end text-success fw-bold">
                                                            0.00
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
        function getSalePurchaseAndProfitData() {
            $('.data_preloader').show();

            var branch_id = $('#branch_id').val();
            var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var filterDate = {
                branch_id,
                child_branch_id,
                from_date,
                to_date,
            }

            $.ajax({
                url: "{{ route('reports.profit.loss.amounts') }}",
                type: 'get',
                data: filterDate,
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getSalePurchaseAndProfitData();

        //Send sale purchase profit filter request
        $('#profit_loss_filter_form').on('submit', function(e) {
            e.preventDefault();
            getSalePurchaseAndProfitData();
        });

        $(document).on('change', '#branch_id', function(e) {
            e.preventDefault();

            var branchId = $(this).val();

            $('#child_branch_id').empty();
            $('#child_branch_id').append('<option data-child_branch_name="" value="">'+"{{ __('All') }}"+'</option>');

            if (branchId == '') {
                return;
            }

            var route = '';
            var url = "{{ route('branches.parent.with.child.branches', ':branchId') }}";
            route = url.replace(':branchId', branchId);

            $.ajax({
                url: route,
                type: 'get',
                success: function(branch) {

                    if (branch.child_branches.length > 0) {

                        $('#child_branch_id').empty();
                        $('#child_branch_id').append('<option data-child_branch_name="'+"{{ __('All') }}"+'" value="">'+"{{ __('All') }}"+'</option>');
                        $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + branch.area_name + ')' + '" value="' + branch.id + '">' + branch.name + '(' + branch.area_name + ')' + '</option>');

                        $.each(branch.child_branches, function(key, childBranch) {

                            $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + childBranch.area_name + ')' + '" value="' + childBranch.id + '">' + branch.name + '(' + childBranch.area_name + ')' +'</option>');
                        });
                    }
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        //Print Profit/Loss
        $(document).on('click', '#printReport', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.profit.loss.print') }}";
            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
            var child_branch_name = $('#child_branch_id').find('option:selected').data('child_branch_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var filterDate = {
                branch_id,
                branch_name,
                child_branch_id,
                child_branch_name,
                from_date,
                to_date,
            }

            $.ajax({
                url: url,
                type: 'get',
                data: filterDate,
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
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
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
