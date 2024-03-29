<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 15px;
        margin-right: 15px;
    }

    .header,
    .header-space,
    .footer,
    .footer-space {
        height: 20px;
    }

    .header {
        position: fixed;
        top: 0;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }

    .noBorder {
        border: 0px !important;
    }

    tr.noBorder td {
        border: 0px !important;
    }

    tr.noBorder {
        border: 0px !important;
        border-left: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
            <p><b>@lang('menu.all_business_location')</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city . ', ' . $branch->state . ', ' . $branch->zip_code . ', ' . $branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>@lang('menu.date') </b>
                {{ date($generalSettings['business_or_shop__date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date($generalSettings['business_or_shop__date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('menu.total_cash_statement') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        @php
            $totalCashFlow = 0;
        @endphp
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="aiability_area">
                        <table class="table table-sm">
                            <tbody>
                                {{-- Cash Flow from operations --}}
                                @php
                                    $oparationTotal = 0;
                                @endphp
                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('menu.cash_flow_from_operations') </strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.net_profit_before_tax') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['net_profit_before_tax']) }}</em>
                                        @php
                                            $oparationTotal += $netProfitLossAccount['net_profit_before_tax'];
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.customer_balance') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>({{ App\Utils\Converter::format_in_bdt($customerReceivable->sum('total_due')) }})</em>
                                        @php
                                            $oparationTotal -= $customerReceivable->sum('total_due');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.supplier_balance') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($supplierPayable->sum('total_due')) }}</em>
                                        @php
                                            $oparationTotal += $supplierPayable->sum('total_due');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.current_stock_value') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>({{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['closing_stock']) }})</em>
                                        @php
                                            $oparationTotal -= $netProfitLossAccount['closing_stock'];
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.current_asset') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($currentAssets->sum('total_current_asset')) }}</em>
                                        @php
                                            $oparationTotal += $currentAssets->sum('total_current_asset');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.current_liability') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($currentLiability->sum('current_liability')) }}</em>
                                        @php
                                            $oparationTotal += $currentLiability->sum('current_liability');
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.tax_payable') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>{{ App\Utils\Converter::format_in_bdt($netProfitLossAccount['tax_payable']) }}</em>
                                        @php
                                            $oparationTotal += $netProfitLossAccount['tax_payable'];
                                        @endphp
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>@lang('menu.total_operations') :
                                                ({{ $generalSettings['business_or_shop__currency_symbol'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b>{{ $oparationTotal < 0 ? '(' . App\Utils\Converter::format_in_bdt($oparationTotal) . ')' : App\Utils\Converter::format_in_bdt($oparationTotal) }}</b>
                                        @php
                                            $totalCashFlow += $oparationTotal;
                                        @endphp
                                    </td>
                                </tr>

                                {{-- Cash Flow from investing --}}

                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('menu.cash_flow_from_investing') </strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.fixed_asset') </em>
                                    </td>

                                    <td class="text-start">
                                        <em>
                                            ({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})
                                        </em>
                                    </td>
                                    @php
                                        $totalCashFlow -= $fixedAssets->sum('total_fixed_asset');
                                    @endphp
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>@lang('menu.total_investing') :
                                                ({{ $generalSettings['business_or_shop__currency_symbol'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b><em>({{ App\Utils\Converter::format_in_bdt($fixedAssets->sum('total_fixed_asset')) }})</em> </b>
                                    </td>
                                </tr>

                                {{-- Cash Flow from financing --}}
                                <tr>
                                    <th class="text-start" colspan="2">
                                        <strong>@lang('menu.cash_flow_form_financing') </strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.capital_ac') </em>
                                    </td>
                                    <td class="text-start">0.00</td>
                                </tr>

                                <tr>
                                    <td class="text-start">
                                        <em>@lang('menu.loan_and_advance') </em>
                                    </td>
                                    <td class="text-start">({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</td>
                                </tr>

                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>@lang('menu.total_financing') :
                                                ({{ $generalSettings['business_or_shop__currency_symbol'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b>
                                            <em>({{ App\Utils\Converter::format_in_bdt($loanAndAdvance->sum('current_loan_receivable')) }})</em>
                                        </b>
                                        @php
                                            $totalCashFlow -= $loanAndAdvance->sum('current_loan_receivable');
                                        @endphp
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end">
                                        <b>
                                            <em>
                                                @lang('menu.total_cash_flow') : ({{ $generalSettings['business_or_shop__currency_symbol'] }})
                                            </em>
                                        </b>
                                    </td>

                                    <td class="text-start">
                                        <b class="total_cash_flow">
                                            <em>
                                                {{ $totalCashFlow < 0 ? '(' . App\Utils\Converter::format_in_bdt($totalCashFlow) . ')' : App\Utils\Converter::format_in_bdt($totalCashFlow) }}
                                            </em>
                                            </span>
                                            </th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date') {{ date('d-m-Y , h:iA') }}
    </small>
</div>
