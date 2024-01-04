<div class="account_summary_area">
    <div class="heading">
        <h5 class="py-1 pl-1 text-center">@lang('menu.account_summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end"><strong>@lang('menu.opening_balance') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end opening_balance" id="ledger_opening_balance"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_purchase') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end total_purchase" id="ledger_total_purchase"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_paid') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end text-success total_paid" id="ledger_total_paid"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_return') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end total_return" id="ledger_total_return"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_less') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end total_less" id="ledger_total_less"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.balance_due') : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end text-danger total_purchase_due" id="ledger_total_purchase_due"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Returnable/Refundable Amount') }} : {{ $generalSettings['business__currency_symbol'] }}</strong></td>
                    <td class="text-end total_purchase_return_due" id="ledger_total_purchase_return_due"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
