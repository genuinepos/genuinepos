<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Shop/Business") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Price(As Per Plan)") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Renewed Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Renewed Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        @if ($transaction?->details?->has_business == 1)
            <tr>
                <td style="font-size:11px!important;">Multi Shop Management System(Business)</td>
                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->data?->business_price_period == 'month')
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_price_per_month) }}
                    @elseif ($transaction?->details?->data?->business_price_period == 'year')
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_price_per_year) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_lifetime_price) }}
                    @endif
                </td>
                <td style="font-size:11px!important;">{{ $transaction?->details?->data?->business_price_period }}</td>
                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->data?->business_price_period == 'lifetime')
                        {{ __("Lifetime") }}
                    @else
                        {{ $transaction?->details?->data?->business_price_period_count }}
                    @endif
                </td>
                <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_subtotal) }}</td>
            </tr>
        @endif

        @if (isset($transaction?->details?->data?->branch_names) && count($transaction?->details?->data?->branch_names) > 0)
            @foreach ($transaction?->details?->data?->branch_names as $index => $branchName)
                <td style="font-size:11px!important;">{{ $branchName }}</td>

                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->data?->shop_price_periods[$index] == 'month')
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->shop_price_per_month) }}
                    @elseif ($transaction?->details?->data?->shop_price_periods[$index] == 'year')
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->shop_price_per_year) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->shop_lifetime_price) }}
                    @endif
                </td>

                <td style="font-size:11px!important;">{{ $transaction?->details?->data?->shop_price_periods[$index] }}</td>

                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->data?->shop_price_periods[$index] == 'lifetime')
                        {{ __("Lifetime") }}
                    @else
                        {{ $transaction?->details?->data?->shop_price_period_counts[$index] }}
                    @endif
                </td>

                <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->shop_subtotals[$index]) }}</td>
            @endforeach
        @endif

        <tr style="border-top: 1px solid">
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction->net_total) }}</td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->discount) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>

            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
            </td>
        </tr>
    </tbody>
</table>
