@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table.table.modal-table.table-sm.table-bordered.financial_report_table td {
            font-size: 13px;
            font-weight: 400;
        }
    </style>
@endpush
@section('title', 'Profit Loss A/C - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Financial Report') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_financial_report">
                                <div class="form-group row">

                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                        <div class="col-md-3">
                                            <label><strong>{{ __('Shop/Business') }} </strong></label>
                                            <select name="branch_id" id="branch_id" class="form-control select2" autofocus>
                                                <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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

            <div class="col-md-8">
                <div class="card">
                    <div class="section-header">
                        <h6>{{ __('Financial Report') }}</h6>
                    </div>

                    <div class="widget_content mt-2">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>
                        <div class="table-responsive" id="data-list">
                            <table class="table modal-table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="aiability_area">
                                            <table class="table table-sm">
                                                <tbody>
                                                    {{-- Cash Flow from investing --}}
                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>Assets : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white ps-2" colspan="2">
                                                            <span>Current Assets : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Bank A/c - Branch Access</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Cash In Hand - Fixed Branch Wise</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Deposits - Fixed Branch Wise</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Loan And Advance</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Stock In Hand</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td style="display:flex;margin-left: 20px!important;">Account Receivable</td>
                                                        <td>0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start">Fixed Assets :</th>
                                                        <td class="text-start">0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start">Investments :</th>
                                                        <td class="text-start">0.00</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end fw-bold"><span>Total : </span></td>

                                                        <td>
                                                            <span>0.00</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.sales') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale')</em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale_return') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.purchase') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase_return') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.expenses') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_direct_expense') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Indirect Expense') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.products') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.closing_stock') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_stock_adjustment') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Stock Adjustment Recovered Amount') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.loan_and_liabilities') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan Liabilities') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan & Liabilities Due Paid') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.payable_loan_liabilities_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.loan_and_advance') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_loan_advance') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan & Advance Due Received') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Receivable Loan & Advance Due') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.profit_loss') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Daily Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Gross Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Net Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.account_balance') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Cash-In-Hand Balance') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.bank_ac_balance')</em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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

        function getFinancialReport() {

            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            // console.log(branch_id);
            $.ajax({
                url: "{{ route('reports.financial.amounts') }}",
                type: 'GET',
                data: {
                    branch_id,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getFinancialReport();

        // //Print purchase Payment report
        $(document).on('submit', '#filter_financial_report', function(e) {
            e.preventDefault();
            getFinancialReport();
        });

        //Print financial report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.financial.report.print') }}";
            var branch_id = $('#branch_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
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
