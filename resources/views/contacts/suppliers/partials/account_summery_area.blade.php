<div class="account_summary_area">
    <div class="heading">
        <h5 class="py-1 pl-1 text-center">Account Summary</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end"><strong>Opening Balance : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end opening_balance">{{ App\Utils\Converter::format_in_bdt($supplier->opening_balance) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Purchase : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_purchase">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end text-success total_paid">
                        {{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_return">{{ App\Utils\Converter::format_in_bdt($supplier->total_return) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Less : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_less">{{ App\Utils\Converter::format_in_bdt($supplier->total_less) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Balance Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end text-danger total_purchase_due">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>Total Returnable/Refundable Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_purchase_return_due">
                        {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_return_due) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>