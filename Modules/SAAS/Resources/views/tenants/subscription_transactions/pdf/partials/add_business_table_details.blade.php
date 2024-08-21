<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Description') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Company Price(As Per Plan)') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ __("Multi Store Management System") }}({{ __("Company") }})</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $businessPriceInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->data?->business_price);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($businessPriceInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_price) }}
                @endif

            </td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->data?->business_price_period }}</td>
            @if ($transaction?->details?->data?->business_price_period == 'Lifetime')
                <td style="font-size:11px!important;">{{ __('Lifetime') }}</td>
            @else
                <td style="font-size:11px!important;">{{ $transaction?->details?->data?->business_price_period_count }}</td>
            @endif
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $businessSubtotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->data?->business_subtotal);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($businessSubtotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->data?->business_subtotal) }}
                @endif
            </td>
        </tr>

        <tr style="border-top: 1px solid">
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $netTotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction->net_total);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($netTotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction->net_total) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $discountInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction->discount);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($discountInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction->discount) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : </td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $totalPayableInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction->total_payable_amount);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($totalPayableInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : </td>

            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $paidInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction->paid);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($paidInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="4" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $dueInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction->due);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($dueInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
                @endif
            </td>
        </tr>
    </tbody>
</table>
