<tr>
    <th class="text-start">
        <span>{{ __('Incomes') }} : </span>
    </th>

    <th class="text-start"></th>
</tr>

@foreach ($incomeDetails['incomeAccounts']->groups as $group)
    <tr>
        <td style="display:flex;margin-left: 10px!important;">{{ $group->group_name }}</td>
        <td>:
            @if ($group->closing_balance_side == 'dr')
                <span>(-) {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}</span>
            @else
                <span>{{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}</span>
            @endif
        </td>
    </tr>
@endforeach

<tr>
    <td class="text-end fw-bold"><span>{{ __("Total") }} ({{ $generalSettings['business_or_shop__currency_symbol'] }})</span></td>

    <td class="fw-bold">:
        @if ($incomeDetails['closingBalanceSide'] == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($incomeDetails['closingBalance']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($incomeDetails['closingBalance']) }}</span>
        @endif
    </td>
</tr>
