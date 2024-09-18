<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Description") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Company Price(As Per Plan)") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ __("Multi Store Management System") }}({{ __("Company") }})</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_price) }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->data?->business_price_period }}</td>
            @if ($transaction?->details?->data?->business_price_period == 'Lifetime')

                <td style="font-size:11px!important;">{{ __('Lifetime') }}</td>
            @else

                <td style="font-size:11px!important;">{{ $transaction?->details?->data?->business_price_period_count }}</td>
            @endif
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_subtotal) }}</td>
        </tr>

        <tr style="border-top: 1px solid">
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction->net_total) }}</td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} :</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->discount) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} :</td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} :</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} :</td>

            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} :</td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
            </td>
        </tr>
    </tbody>
</table>
