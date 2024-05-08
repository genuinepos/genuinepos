<tr>
    <th class="text-start">
        <span>{{ __('Expenses') }} : </span>
    </th>

    <th class="text-start"></th>
</tr>

@foreach ($expenseDetails['expenseAccounts']->groups as $group)
    <tr>
        <td style="padding-left: 20px!important;">{{ $group->group_name }}</td>
        <td>:
            @if ($group->closing_balance_side == 'cr')
                <span>(-) {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}</span>
            @else
                <span>{{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}</span>
            @endif
        </td>
    </tr>
@endforeach

<tr>
    <td class="text-end fw-bold"><span>{{ __("Total") }} </span></td>

    <td class="fw-bold">:
        @if ($expenseDetails['closingBalanceSide'] == 'cr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($expenseDetails['closingBalance']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($expenseDetails['closingBalance']) }}</span>
        @endif
    </td>
</tr>
