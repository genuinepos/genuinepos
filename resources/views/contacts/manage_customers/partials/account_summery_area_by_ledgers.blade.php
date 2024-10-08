<style>
    .account_summary_table table tbody td {
        line-height: 0px !important;
        padding: 0px 5px !important;
        height: 19px;
    }
</style>
<div class="account_summary_area">
    <div class="heading py-1">
        <h5 class="py-1 pl-1 text-center">{{ __('Account Summary') }}</h5>
    </div>

    <div class="account_summary_table">
        <table class="display table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end">
                        <strong>{{ __('Opening Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                    </td>

                    <td class="text-end opening_balance" id="ledger_opening_balance">0.00</td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>{{ __('Total Sale') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                    </td>
                    <td class="text-end total_sale" id="ledger_total_sale">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Purchase') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end total_purchase" id="ledger_total_purchase">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Return') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end total_return" id="ledger_total_return">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Received') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end total_received text-success" id="ledger_total_received">
                        0.00
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end total_paid text-danger" id="ledger_total_paid">
                        0.00
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Less') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end total_less" id="ledger_total_less">0</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Closing Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                    <td class="text-end closing_balance text-danger" id="ledger_closing_balance">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
