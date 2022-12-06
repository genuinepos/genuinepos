<div class="account_summary_area">
    <div class="heading py-1">
        <h5 class="py-1 pl-1 text-center">Account Summary</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.opening_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                    </td>

                    <td class="text-end opening_balance"> {{ App\Utils\Converter::format_in_bdt($customer->opening_balance) }}</td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.total_sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                    </td>

                    <td class="text-end total_sale">{{ App\Utils\Converter::format_in_bdt($customer->total_sale) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_return">{{ App\Utils\Converter::format_in_bdt($customer->total_return) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Less : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_less">{{ App\Utils\Converter::format_in_bdt($customer->total_less) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_paid">
                        {{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.balance_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_due">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Returnable Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_return_due">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
