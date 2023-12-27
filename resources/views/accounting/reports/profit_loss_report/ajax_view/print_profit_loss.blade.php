<style>
    @media print {
        table { page-break-after: auto; }

        tr {  page-break-inside: avoid; page-break-after: auto; }

        td { page-break-inside: avoid; page-break-after: auto, font-size:9px !important; }

        thead { display: table-header-group; }

        tfoot { display: table-footer-group; }
    }

    @page { size: a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px; margin-right: 5px; }

    div#footer { position: fixed; bottom: 20px; left: 0px; width: 100%; height: 0%; color: #CCC; background: #333; padding: 0; margin: 0; }

    .print_table th { font-size: 11px !important; font-weight: 550 !important; line-height: 12px !important; }

    .print_table tr td { color: black; font-size: 10px !important; line-height: 12px !important; }

    .print_area { font-family: Arial, Helvetica, sans-serif; }

    .print_area h6 { font-size: 14px !important; }

    .print_area p { font-size: 11px !important; }

    .print_area small { font-size: 8px !important; }
</style>

@php
    $dateFormat = $generalSettings['business__date_format'];
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
                @endif
            @endif
        </div>

        <div class="col-8 text-end">
            <p style="text-transform: uppercase;" class="p-0 m-0">
                <strong>
                    @if (auth()->user()?->branch)
                        @if (auth()->user()?->branch?->parent_branch_id)
                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else
                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business__business_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Profit/Loss') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($dateFormat, strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($dateFormat, strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business__business_name'];
                if (auth()->user()?->branch) {
                    if (auth()->user()?->branch->parentBranch) {
                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                    } else {
                        $ownOrParentbranchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                    }
                }
            @endphp
            <p><strong>{{ __('Shop/Business') }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-6">
            <p><strong>{{ __('Child Shop') }} : </strong> {{ $filteredChildBranchName }} </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-8 offset-2">
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <td class="text-end">
                            <strong>{{ __('Total Sale') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business__currency'] }}</strong>
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalSale']) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Individual Sold Product Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalUnitTax']) }})
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Sale Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalOrderTax']) }})
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end">
                            <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business__currency'] }}</strong>
                        </td>

                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalUnitCost']) }})
                        </td>
                    </tr>

                    @if ($profitLossAmounts['grossProfit'] >= 0)
                        <tr>
                            <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['grossProfit']) }}
                            </td>
                        </tr>
                    @elseif ($profitLossAmounts['grossProfit'] < 0)
                        <tr>
                            <td class="text-end fw-bold"><strong>{{ __('Gross Loss') }} : {{ $generalSettings['business__currency'] }}</strong></td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['grossProfit']) }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Stock Adjustment') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalStockAdjustmentAmount']) }})
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalStockAdjustmentRecovered']) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Expense') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalExpense']) }})
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Sales Return') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalSaleReturn']) }})
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end"><strong>{{ __('Total Expense By Payroll') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                        <td class="text-end">
                            ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalPayrollPayment']) }})
                        </td>
                    </tr>

                    @if ($profitLossAmounts['netProfit'] >= 0)
                        <tr>
                            <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['netProfit']) }}
                            </td>
                        </tr>
                    @elseif ($profitLossAmounts['netProfit'] < 0)
                        <tr>
                            <td class="text-end fw-bold"><strong>{{ __('Net Loss') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['netProfit']) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small>{{ __('Powered By') }} <strong>{{ __('Speed Digit Software Solution') }}.</strong></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>
