<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 text-center">
            @if ($branch_id == '')
                <h6>{{ $generalSettings['business__shop_name'] }}</h6>
                <p><b>@lang('menu.all_business_location').</b></p>
                <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            @elseif ($branch_id == 'NULL')
                <h6>{{ $generalSettings['business__shop_name'] }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            @else
                @php
                    $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')->first();
                @endphp
                <h6>{{ $branch->name.' '.$branch->branch_code }}</h6>
                <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
            @endif

            @if ($fromDate && $toDate)
                <p><b>@lang('menu.date') :</b> {{date($generalSettings['business__date_format'] ,strtotime($fromDate)) }} <b>@lang('menu.to')</b> {{ date($generalSettings['business__date_format'] ,strtotime($toDate)) }} </p>
            @endif
            <h6 style="margin-top: 10px;"><b>@lang('menu.daily_profit_loss_report')</b></h6>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    {{ __('Sold Product Total Unit Cost') }} :
                                    <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">{{ __('Total Order Tax') }} : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalOrderTax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start"> @lang('menu.total_stock_adjustment') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start"> @lang('menu.total_expense') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('menu.total_transfer_shipping_charge') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('menu.total_sell_return') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalReturn) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('menu.total_payroll') :</th>
                                <td class="text-start">{{ $generalSettings['business__currency'] }} {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}</td>
                            </tr>

                            <tr>
                                <th class="text-start">@lang('menu.total_production_cost') :</th>
                                <td class="text-start">{{ $generalSettings['business__currency'] }} 0.00 (P)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-start">
                                    @lang('menu.total_sale') : <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">{{ __('Total Stock Adjustment Recovered') }} : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $grossProfit = ($totalSale + $totalStockAdjustmentRecovered)
                                    - $totalStockAdjustmentAmount
                                    - $totalExpense
                                    - $totalReturn
                                    - $totalOrderTax
                                    - $totalPayroll
                                    - $totalTotalUnitCost
                                    - $totalTransferShipmentCost;
                    @endphp

                    <div class="gross_profit_area">
                        <h6 class="text-muted m-0">{{ __('Total Daily Profit') }} :
                            {{ $generalSettings['business__currency'] }}
                            <span class="{{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($grossProfit) }}</span></h6>
                        <p class="text-muted m-0"><b>Calculate Gross Profit :</b> (Total Sale + Total Stock Adjustment Recovered)
                            <b>-</b> ( Sold Product Total Unit Cost + Total Sale Return + Total Sale Order Tax + Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Payroll + Total Production Cost )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif
