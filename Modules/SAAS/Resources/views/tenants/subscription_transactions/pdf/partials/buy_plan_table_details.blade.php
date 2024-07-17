<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Plan') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Store Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price Period Count') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ $transaction?->plan?->name }}</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $shopPriceInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->shop_price);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($shopPriceInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->shop_price) }}
                @endif
            </td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_count }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period }}</td>
            <td style="font-size:11px!important;">{{ $transaction?->details?->shop_price_period_count }}</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $shopSubtotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->shop_subtotal);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($shopSubtotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->shop_subtotal) }}
                @endif
            </td>
        </tr>

        @if ($transaction?->details?->has_business == 1)
            <tr>
                <td style="font-size:11px!important;">{{ __("Multi Store Management System") }}({{ __("Company") }})</td>
                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->country != 'bangladesh')
                        @php
                            $businessPriceInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->business_price);
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($businessPriceInBdt) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->business_price) }}
                    @endif
                </td>
                <td style="font-size:11px!important;">...</td>
                <td style="font-size:11px!important;">{{ $transaction?->details?->business_price_period }}</td>
                <td style="font-size:11px!important;">{{ $transaction?->details?->business_price_period_count }}</td>
                <td style="font-size:11px!important;">
                    @if ($transaction?->details?->country != 'bangladesh')
                        @php
                            $businessSubtotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->business_subtotal);
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($businessSubtotalInBdt) }}
                    @else
                        {{ App\Utils\Converter::format_in_bdt($transaction?->details?->business_subtotal) }}
                    @endif
                </td>
            </tr>
        @endif


        <tr style="border-top: 1px solid">
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :</td>
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
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : </td>
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
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : </td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : </td>
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
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : </td>

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
            <td colspan="5" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : </td>
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
