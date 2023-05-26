
<div class="daily_profit_loss_amount_area">
    <div class="row g-3">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="display table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong> @lang('menu.total_sale') <small>({{__('Inc. Tax')}})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                </td>

                                <td class="text-end text-success">
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-success">
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end">
                                    <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{__('Inc. Tax')}})</small> : {{ $generalSettings['business__currency'] }}</strong>
                                </td>

                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }})
                                </td>
                            </tr>

                            @php
                                $grossProfit = ($totalSale + $totalStockAdjustmentRecovered) - $totalTotalUnitCost
                            @endphp

                            <tr>
                                <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                @if ($grossProfit >= 0)
                                    <td class="text-end text-success fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($grossProfit) }}
                                    </td>
                                @elseif ($grossProfit < 0)
                                    <td class="text-end text-danger fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($grossProfit) }}
                                    </td>
                                @endif
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Order Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalOrderTax) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_stock_adjustment') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_expense') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalExpense) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_transfer_shipping_charge') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_sell_return') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalSaleReturn) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>@lang('menu.total_payroll') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($totalPayroll) }})
                                </td>
                            </tr>

                            {{--<tr>
                                <td class="text-end"><strong>@lang('menu.total_production_cost') : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="text-end"> 0.00 (P)</td>
                            </tr> --}}

                            @php
                                $netProfit = $grossProfit
                                            - $totalStockAdjustmentAmount
                                            - $totalExpense
                                            - $totalSaleReturn
                                            - $totalOrderTax
                                            - $totalPayroll
                                            - $totalTransferShipmentCost;
                            @endphp

                            <tr>
                                <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                @if ($netProfit >= 0)
                                    <td class="text-end text-success fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($netProfit) }}
                                    </td>
                                @elseif ($netProfit < 0)
                                    <td class="text-end text-danger fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($netProfit) }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
