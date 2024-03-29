<table class="table modal-table table-sm table-bordered financial_report_table">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="display table table-sm">
                    <tbody>
                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.asset') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.fixed_asset') </em>
                            </td>
                            <td class="text-end"><b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['fixed_asset_balance']) }}</em></b>  </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.purchase') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.total_purchase') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_paid') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_paid']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_purchase_due') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_due']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_purchase_return') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_purchase_return']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.sales') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale')</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale']) }}</em></b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_payment_received') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_paid']) }}</em></b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale_due') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_due']) }}</em></b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale_return') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_sale_return']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.expenses') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_direct_expense') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_direct_expense']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Total Indirect Expense') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_indirect_expense']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.products') </span>
                            </th>
                        </tr>

                        @if (!$from_date)
                            <tr>
                                <td class="text-start">
                                    <em> @lang('menu.closing_stock') (<small>@lang('menu.non_filterable_by_date')</small>) </em>
                                </td>

                                <td class="text-end">
                                    <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['closing_stock']) }}</em> </b>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_stock_adjustment') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Total Stock Adjustment Recovered Amount') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_adjusted_recovered']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.profit_loss') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.daily_profit') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['daily_profit']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.gross_profit') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['gross_profit']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.net_profit') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['net_profit']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.account_balance') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Cash-In-Hand Balance') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['cash_in_hand']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.bank_ac_balance')</em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['bank_account']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.loan_and_advance') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_loan_advance') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance']) }}</em></b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Total Loan & Advance Due Received') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance_received']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Receivable Loan & Advance Due') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_advance_due']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start bg-secondary text-white" colspan="2">
                                <span>@lang('menu.loan_and_liabilities') </span>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Total Loan Liabilities') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>{{ __('Total Loan & Liabilities Due Paid') }} </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability_paid']) }}</em> </b>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.payable_loan_liabilities_due') </em>
                            </td>

                            <td class="text-end">
                                <b><em>{{ App\Utils\Converter::format_in_bdt($allFinancialAmounts['total_loan_and_liability_due']) }}</em> </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
