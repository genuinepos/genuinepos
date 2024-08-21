<div class="daily_profit_loss_amount_area">
    <div class="row g-3">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="display table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <strong>{{ __('Total Sale') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                </td>

                                <td class="text-end text-success">
                                    {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalSale']) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Individual Sold Product Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalUnitTax']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalOrderTax']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end">
                                    <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                </td>

                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalUnitCost']) }})
                                </td>
                            </tr>

                            @if ($profitLossAmounts['grossProfit'] >= 0)
                                <tr>
                                    <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>

                                    <td class="text-end text-success fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['grossProfit']) }}
                                    </td>
                                </tr>
                            @elseif ($profitLossAmounts['grossProfit'] < 0)
                                <tr>
                                    <td class="text-end fw-bold text-danger"><strong>{{ __('Gross Loss') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>

                                    <td class="text-end text-danger fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['grossProfit']) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Adjustment') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalStockAdjustmentAmount']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-success">
                                    {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalStockAdjustmentRecovered']) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Expense') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalExpense']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Sales Return') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalSaleReturn']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Stock Issue') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalStockIssue']) }})
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __('Total Expense By Payroll') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                <td class="text-end text-danger">
                                    ({{ App\Utils\Converter::format_in_bdt($profitLossAmounts['totalPayrollPayment']) }})
                                </td>
                            </tr>

                            @if ($profitLossAmounts['netProfit'] >= 0)
                                <tr>
                                    <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                    <td class="text-end text-success fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['netProfit']) }}
                                    </td>
                                </tr>
                            @elseif ($profitLossAmounts['netProfit'] < 0)
                                <tr>
                                    <td class="text-end fw-bold text-danger"><strong>{{ __('Net Loss') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ App\Utils\Converter::format_in_bdt($profitLossAmounts['netProfit']) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
