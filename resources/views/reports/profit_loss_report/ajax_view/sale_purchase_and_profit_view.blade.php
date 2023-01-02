
<div class="daily_profit_loss_amount_area">
    <div class="row g-3">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="display table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong>{{ __('Sold Product Total Unit Cost') }} : {{ $generalSettings['business']['currency'] }}</strong>
                                    <br>
                                    <small>(Inc.Tax)</small>
                                </td>

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Order Tax') }} : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalOrderTax) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_stock_adjustment') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_expense') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_transfer_shipping_charge') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_sell_return') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalSaleReturn) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_payroll') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_production_cost') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="text-end"> 0.00 (P)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="display table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong> @lang('menu.total_sale') : {{ $generalSettings['business']['currency'] }}</strong><br>
                                    <small>(Inc.Tax)</small>
                                </td>

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business']['currency'] }}:</strong></td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @php
                $grossProfit = ($totalSale + $totalStockAdjustmentRecovered)
                            - $totalStockAdjustmentAmount
                            - $totalExpense
                            - $totalSaleReturn
                            - $totalOrderTax
                            - $totalPayroll
                            - $totalTotalUnitCost
                            - $totalTransferShipmentCost;
            @endphp

            <div class="card mt-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="gross_profit_area">
                                <h6 class="text-muted m-0">{{ __('Total Daily Profit') }} :
                                    {{ $generalSettings['business']['currency'] }}
                                    <span class="{{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($grossProfit) }}</span></h6>
                                    <p class="text-muted m-0">@lang('menu.gross_profit') (Total Sale + Total Stock Adjustment Recovered)
                                        - <br>( Sold Product Total Unit Cost + Total Sale Return + Total Sale Order Tax + Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Payroll + Total Production Cost )</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>