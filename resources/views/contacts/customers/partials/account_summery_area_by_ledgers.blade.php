<div class="account_summary_area">
    <div class="heading py-1">
        <h5 class="py-1 pl-1 text-center">@lang('menu.account_summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="display table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.opening_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> 
                    </td>

                    <td class="text-end opening_balance" id="ledger_opening_balance">0.00</td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.total_sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                    </td>

                    <td class="text-end total_sale" id="ledger_total_sale">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_return" id="ledger_total_return">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_less') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_less" id="ledger_total_less">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_paid text-success" id="ledger_total_paid">
                        0.00
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.balance_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_due text-danger" id="ledger_total_sale_due">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Returnable Due') }}  : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_return_due" id="ledger_total_sale_return_due">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>