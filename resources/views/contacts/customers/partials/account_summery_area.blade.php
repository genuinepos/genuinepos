<div class="account_summary_area">
    <div class="heading py-1">
        <h5 class="py-1 pl-1 text-center">@lang('menu.account_summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="display table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.opening_balance') : {{ $generalSettings['business']['currency'] }}</strong>
                    </td>

                    <td class="text-end opening_balance"> {{ App\Utils\Converter::format_in_bdt($customer->opening_balance) }}</td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.total_sale') : {{ $generalSettings['business']['currency'] }}</strong>
                    </td>

                    <td class="text-end total_sale">{{ App\Utils\Converter::format_in_bdt($customer->total_sale) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_return') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_return">{{ App\Utils\Converter::format_in_bdt($customer->total_return) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_less') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_less">{{ App\Utils\Converter::format_in_bdt($customer->total_less) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_paid') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_paid text-success">
                        {{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.balance_due') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_sale_due text-danger">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Returnable Due') }} : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_sale_return_due">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
