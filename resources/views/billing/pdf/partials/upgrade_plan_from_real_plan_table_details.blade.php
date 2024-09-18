<table class="table print-table table-sm table-bordered">
    <thead>
        <tr style="border-bottom: 1px solid">
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Upgraded Plan") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Total Price") }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Adjusted Amount') }}</th>
            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
        </tr>
    </thead>
    <tbody class="sale_print_product_list">
        <tr>
            <td style="font-size:11px!important;">{{ $transaction?->plan?->name }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->net_total) }}</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->total_adjusted_amount) }}</td>
            @php
                $netTotal =  $transaction?->details?->net_total ? (float) $transaction?->details?->net_total : 0;
                $adjustedAmount =  $transaction?->details?->total_adjusted_amount ? (float) $transaction?->details?->total_adjusted_amount : 0;
                $subtotal = $netTotal - $adjustedAmount;
            @endphp
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($subtotal) }}</td>
        </tr>

        <tr style="border-top: 1px solid">
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total') }} :</td>
            <td style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($transaction?->details?->net_total) }}</td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Adjusted Amount') }} : </td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction?->details?->total_adjusted_amount) }}
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Discount') }} : </td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction?->details?->discount) }}
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
                {{ App\Utils\Converter::format_in_bdt($transaction->total_payable_amount) }}
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Paid') }} : </td>

            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->paid) }}
            </td>
        </tr>

        <tr>
            <td colspan="3" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due') }} : </td>
            <td style="font-size:11px!important;">
                {{ App\Utils\Converter::format_in_bdt($transaction->due) }}
            </td>
        </tr>
    </tbody>
</table>
