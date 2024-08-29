<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Upgraded Plan') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Total Price') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Adjusted Amount') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ $transaction?->plan?->name }}</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $netTotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->net_total);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($netTotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->net_total) }}
                @endif
            </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $adjustedAmountInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->total_adjusted_amount);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($adjustedAmountInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->total_adjusted_amount) }}
                @endif
            </td>
            @php
                $netTotal = $transaction?->details?->net_total ? (float) $transaction?->details?->net_total : 0;
                $adjustedAmount = $transaction?->details?->total_adjusted_amount ? (float) $transaction?->details?->total_adjusted_amount : 0;
                $subtotal = $netTotal - $adjustedAmount;
            @endphp

            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $subtotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($subtotal);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($subtotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($subtotal) }}
                @endif
            </td>
        </tr>

        <tr style="border-top: 1px solid">
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :</td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $netTotalInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->net_total);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($netTotalInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->net_total) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Adjusted Amount') }} : </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $adjustedAmountInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->total_adjusted_amount);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($adjustedAmountInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->total_adjusted_amount) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : </td>
            <td style="font-size:11px!important;">
                @if ($transaction?->details?->country != 'bangladesh')
                    @php
                        $discountInBdt = \Modules\SAAS\Utils\AmountInBdtCurrency::amountInBdt($transaction?->details?->discount);
                    @endphp
                    {{ App\Utils\Converter::format_in_bdt($discountInBdt) }}
                @else
                    {{ App\Utils\Converter::format_in_bdt($transaction?->details?->discount) }}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Tax') }} : </td>
            <td style="font-size:11px!important;">
                FREE
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Payable') }} : </td>
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
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : </td>
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
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : </td>
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
