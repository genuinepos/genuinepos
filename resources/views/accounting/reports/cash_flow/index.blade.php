@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>


        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 100%;
            padding: 0px;
        }

        /* Clearfix (clear floats) */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            vertical-align: baseline;
        }

        table.group_account_table tr {
            line-height: 16px;
        }

        table {
            border: none !important;
        }

        td.outflow_area {
            border-left: 1px solid #000;
        }

        table.gross_total_balance tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        .net_total_balance_footer tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        .net_credit_total {
            border-left: 1px solid #000;
        }

        td.inflow_area {
            line-height: 17px;
            padding-right: 6px;
        }

        td.outflow_area {
            line-height: 17px;
        }

        /* font-family: sans-serif; */
        td.first_td {
            width: 72%;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }
    </style>
@endpush
@section('title', 'Cash Flow - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Cash Flow') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_cash_report">
                                <div class="form-group row">
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                        <div class="col-md-2">
                                            <label><strong>{{ __('Shop/Business') }} </strong></label>
                                            <select name="branch_id" id="branch_id" class="form-control select2" autofocus>
                                                <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option data-branch_name="{{ $branch->name }}" value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>{{ __('Chain Shop') }} </strong></label>
                                            <select name="child_branch_id" class="form-control select2" id="child_branch_id">
                                                <option data-child_branch_name="" value="">{{ __('Select Shop First') }}</option>
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <label><strong>{{ __('From Date') }} : </strong></label>
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
                                        <label><strong>{{ __('Format of Report') }}</strong></label>
                                        <div class="input-group">
                                            <select name="format_of_report" class="form-control" id="format_of_report">
                                                <option value="detailed">{{ __('Detailed') }}</option>
                                                <option value="condensed">{{ __('Condensed') }}</option>
                                            </select>
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

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="p-2">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                            </div>
                            <div class="cash_flow_area">
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="w-100">
                                        <thead>
                                            <tr>
                                                <th class="header_text ps-1 text-center">{{ __('INFLOW') }}</th>
                                                <th class="header_text ps-1 text-center" style="border-left: 1px solid black;">{{ __('OUTFLOW') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td class="inflow_area">
                                                    <table class="capital_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Capital Account') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Capital A/c') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>1,200.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Loan Liabilities') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('City Bank') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="current_assets_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Current Assets') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Closing Stocks') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Diposits (Asset)') }}</a></td>
                                                                        <td class="group_account_balance text-end">
                                                                            1,200.00</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Account Receivable') }}</a>
                                                                        </td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="current_liabilities_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Current Liabilities') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Duties & Taxes') }}</a></td>
                                                                        <td class="group_account_balance text-end">2,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class="text-end"><strong>2,200.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="fixed_assets_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Fixed Assets') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Furniture') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="investments_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Investments') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Advertisement') }}</a></td>
                                                                        <td class="group_account_balance text-end">
                                                                            1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td class="outflow_area">
                                                    <table class="capital_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Capital Account') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Capital A/c') }}</a></td>
                                                                        <td class="group_account_balance text-end">
                                                                            1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>1,200.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Loan Liabilities') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('City Bank') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="current_assets_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Current Assets') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Closing Stocks') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Diposits (Asset)') }}</a>
                                                                        </td>
                                                                        <td class="group_account_balance text-end">
                                                                            1,200.00</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Account Receivable') }}</a>
                                                                        </td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="current_liabilities_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Current Liabilities') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Duties & Taxes') }}</a></td>
                                                                        <td class="group_account_balance text-end">2,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class="text-end"><strong>2,200.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="fixed_assets_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Fixed Assets') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Furniture') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>

                                                    <table class="investments_account_group_table w-100 mt-1">
                                                        <tr>
                                                            <td>
                                                                <strong class="ps-2">{{ __('Investments') }}</strong>
                                                                <table class="group_account_table ms-2">
                                                                    <tr>
                                                                        <td class="group_account_name ps-1"><a href="#">{{ __('Advertisement') }}</a></td>
                                                                        <td class="group_account_balance text-end">1,200.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                            <td class="text-end"><strong>12,400.00</strong></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tfoot class="net_total_balance_footer">
                                            <tr>
                                                <td class="text-end fw-bold net_debit_total">{{ __('Total') }} : 20,000.00</td>
                                                <td class="text-end fw-bold net_credit_total">{{ __('Total') }} : 20,000.00</td>
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
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getCashFlow() {

            $('.data_preloader').show();

            var branch_id = $('#branch_id').val();
            var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();

            $.ajax({
                url: "{{ route('reports.cash.flow.data') }}",
                type: 'GET',
                data: {
                    branch_id,
                    child_branch_id,
                    from_date,
                    to_date,
                    format_of_report,
                },
                success: function(data) {

                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getCashFlow();

        // //Print purchase Payment report
        $(document).on('submit', '#filter_cash_report', function(e) {
            e.preventDefault();
            getCashFlow();
        });

        //Print financial report
        $(document).on('click', '#printReport', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.cash.flow.print') }}";

            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var child_branch_id = $('#child_branch_id').val();
            var child_branch_name = $('#child_branch_id').find('option:selected').data('child_branch_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();

            var currentTitle = document.title;

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    child_branch_id,
                    child_branch_name,
                    from_date,
                    to_date,
                    format_of_report
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

                    var tempElement = document.createElement('div');
                    tempElement.innerHTML = data;
                    var filename = tempElement.querySelector('#title');

                    document.title = filename.innerHTML;

                    setTimeout(function() {
                        document.title = currentTitle;
                    }, 2000);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('change', '#branch_id', function(e) {
            e.preventDefault();

            var branchId = $(this).val();

            $('#child_branch_id').empty();
            $('#child_branch_id').append('<option data-child_branch_name="" value="">' + "{{ __('All') }}" + '</option>');

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
                        $('#child_branch_id').append('<option data-child_branch_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');
                        $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + branch.area_name + ')' + '" value="' + branch.id + '">' + branch.name + '(' + branch.area_name + ')' + '</option>');

                        $.each(branch.child_branches, function(key, childBranch) {

                            $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + childBranch.area_name + ')' + '" value="' + childBranch.id + '">' + branch.name + '(' + childBranch.area_name + ')' + '</option>');
                        });
                    }
                },
                error: function(err) {

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
