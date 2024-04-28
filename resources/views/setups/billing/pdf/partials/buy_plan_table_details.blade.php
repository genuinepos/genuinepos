<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Plan") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Price") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Shop Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ $transaction?->plan?->name }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->shop_price) }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_count }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period_count }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->shop_subtotal) }}</td>
        </tr>

        @if ($transaction?->details?->has_business == 1)
            <tr>
                <td style="font-size:11px!important;">Multi Shop Management System(Business)</td>
                <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->business_price) }}</td>
                <td style="font-size:11px!important;">...</td>
                <td style="font-size:11px!important;">{{ $transaction?->details?->business_price_period }}</td>
                <td style="font-size:11px!important;">{{ $transaction?->details?->business_price_period_count }}</td>
                <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->business_subtotal) }}</td>
            </tr>
        @endif


        <tr style="border-top: 1px solid">
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction->net_total) }}</td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->discount) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>

            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
            </td>
        </tr>
    </tbody>
</table>
