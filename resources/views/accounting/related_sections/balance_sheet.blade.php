@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>@lang('menu.balance_sheet')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>

                        <div class="p-3">

                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form_element rounded mt-0 mb-3">
                                            <div class="element-body">
                                                <form id="filter_form">
                                                    <div class="form-group row">

                                                        <div class="col-md-2">
                                                            <label><strong>@lang('menu.business_location') </strong></label>
                                                            <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                                <option SELECTED value="NULL">{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start">
                                                                    <i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="data_preloader mt-5 pt-5">
                                                <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                                            </div>
                                            <div class="balance_sheet_area">
                                                <div class="print_header d-hide">
                                                    <div class="text-center pb-3">
                                                        <h5>
                                                            @if (auth()->user()->branch_id)
                                                                {{ auth()->user()->branch->name . '/' . auth()->user()->branch->branch_code }}
                                                            @else
                                                                {{ $generalSettings['business_or_shop__business_name'] }}
                                                            @endif
                                                        </h5>
                                                        <h6 class="mt-2"><strong>@lang('menu.balance_sheet')</h6>
                                                    </div>
                                                </div>
                                                <div id="data-list">
                                                    <div class="table-responsive">
                                                        <table class="table modal-table table-sm table-bordered">
                                                            <thead>
                                                                <tr class="bg-secondary">
                                                                    <th class="liability text-white">@lang('menu.liability')</th>
                                                                    <th class="assets text-white">@lang('menu.assets')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="aiability_area">
                                                                        <table class="table table-sm">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.supplier_due') </strong></td>
                                                                                    <td class=" text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="supplier_due"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.customer_return_due') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="customer_return_due"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start">
                                                                                        <strong>@lang('menu.payable_loan_liabilities') </strong>
                                                                                    </td>

                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="payable_ll"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start">
                                                                                        <strong>@lang('menu.capital_ac') </strong>
                                                                                    </td>

                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="payable_ll"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.opening_stock') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="payable_ll"></span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>

                                                                    <td class="asset_area">
                                                                        <table class="table table-sm">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.cash_in_hand') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="cash_in_hand"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.bank_ac_balance') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="bank_balance"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.customer_due') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="customer_due"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.supplier_return_due') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="supplier_return_due"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.current_stock_value') </strong></td>
                                                                                    <td class=" text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="stock_value"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.investments') </strong></td>
                                                                                    <td class=" text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="investment"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.receivable_loan_advance') </strong></td>
                                                                                    <td class=" text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="receiveable_la"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr class="bg-info">
                                                                                    <td class="text-end text-white"><strong>@lang('menu.total_current_asset') </strong></td>
                                                                                    <td class=" text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="total_physical_asset"></span>
                                                                                    </td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td class="text-end text-white"></td>
                                                                                    <td class="text-end"></td>
                                                                                </tr>

                                                                                <tr class="bg-secondary">
                                                                                    <th colspan="2" class="text-start"><strong>@lang('menu.fixed_asset') </strong></th>
                                                                                </tr>

                                                                                <tr class="account_balance_list_area">
                                                                                    <td colspan="2">
                                                                                        <table class="table table-sm">
                                                                                            <tbody class="account_balance_list">
                                                                                                <tr>
                                                                                                    <td class="text-start" colspan="2">
                                                                                                        @lang('menu.furniture') :
                                                                                                    </td>
                                                                                                </tr>

                                                                                                <tr>
                                                                                                    <td class="text-start" colspan="2">
                                                                                                        @lang('menu.vehicles') :
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="bg-secondary">
                                                                    <td class="total_liability_area">
                                                                        <table class="table table-sm">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.total_liability') </strong> </td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="total_liability"></span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        </th>
                                                                    <td class="total_asset_area">
                                                                        <table class="table table-sm">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-start"><strong>@lang('menu.total_asset') </strong></td>
                                                                                    <td class="text-end">
                                                                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                                                        <span class="total_asset"></span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="print_btn_area">
                                                <a id="print_btn" href="#" class="btn btn-sm btn-primary float-end"><i class="fas fa-print"></i>@lang('menu.print')</a>
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
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>
    <script>
        function getBalanceAmounts() {
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            $.ajax({
                url: "{{ route('accounting.balance.sheet.amounts') }}",
                type: 'GET',
                data: {
                    branch_id: branch_id
                },
                success: function(data) {
                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getBalanceAmounts();

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            getBalanceAmounts();
        });

        // Print single payment details
        $('#print_btn').on('click', function(e) {
            e.preventDefault();
            var body = $('.balance_sheet_area').html();
            var header = $('.print_header').html();
            var footer = $('.print_footer').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/balance.sheet.print.css') }}",
                removeInline: false,
                printDelay: 600,
                header: header,
                footer: footer
            });
        });
    </script>
@endpush
