<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Plan") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Increased Store Count") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Per Store(As Per Plan)') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ $transaction?->plan?->name }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->increase_shop_count }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->shop_price) }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period }}</td>
            @if ($transaction?->details?->shop_price_period == 'lifetime')

                <td style="font-size:11px!important;">{{ __('Lifetime') }}</td>
            @else

                <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period_count }}</td>
            @endif

            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->net_total) }}</td>
        </tr>

        <tr style="border-top: 1px solid">
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->net_total) }}</td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction?->discount) }}
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
