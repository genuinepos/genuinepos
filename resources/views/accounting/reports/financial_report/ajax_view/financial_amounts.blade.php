<style>
    td.aiability_area td {
        font-size: 11px;
        padding: 0px;
        margin: 0px !important;
        line-height: 1.5;
        height: 20px;
    }
</style>
<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Assets --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.assets')
                        {{-- Assets End --}}

                        {{-- Liabilities --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.liabilities')
                        {{-- Liabilities End --}}

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
