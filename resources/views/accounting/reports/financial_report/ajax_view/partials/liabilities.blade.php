<tr>
    <th class="text-start">
        <span>{{ __('Liabilities') }} : </span>
    </th>

    <th class="text-start"></th>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">{{ $liabilityDetails['branchAndDivision']->main_group_name }} :</td>
    <td class="text-start">:
        @if ($liabilityDetails['branchAndDivision']->closing_balance_side == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['branchAndDivision']->closing_balance) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['branchAndDivision']->closing_balance) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">{{ $liabilityDetails['capitalAccount']->main_group_name }} :</td>
    <td class="text-start">:
        @if ($liabilityDetails['branchAndDivision']->closing_balance_side == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['capitalAccount']->closing_balance) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['capitalAccount']->closing_balance) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">
        Current Liabilities
    </td>

    <td class="text-start"></td>
</tr>

@foreach ($liabilityDetails['currentLiabilities']->groups as $group)
    <tr>
        <td style="display:flex;margin-left: 20px!important;">{{ $group->group_name }}</td>
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
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">
        Loan (Liabilities)
    </td>
    <td class="text-start"></td>
</tr>

@foreach ($liabilityDetails['loanLiabilities']->groups as $group)
    <tr>
        <td style="display:flex;margin-left: 20px!important;">{{ $group->group_name }}</td>
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
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">{{ $liabilityDetails['suspenseAccount']->main_group_name }} :</td>
    <td class="text-start">:
        @if ($liabilityDetails['suspenseAccount']->closing_balance_side == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['suspenseAccount']->closing_balance) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['suspenseAccount']->closing_balance) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">
        Profit Loss
    </td>
    <td></td>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">{{ $liabilityDetails['grossProfitSide'] == 'dr' ? __('Gross Loss') : __('Gross Profit') }} :</td>
    <td class="text-start">:
        @if ($liabilityDetails['grossProfitSide'] == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['absGrossProfit']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['absGrossProfit']) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-start fw-bold" style="display:flex;margin-left: 10px!important;">{{ $liabilityDetails['netProfitSide'] == 'dr' ? __('Net Loss') : __('Net Profit') }} :</td>
    <td class="text-start">:
        @if ($liabilityDetails['netProfitSide'] == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['absNetProfit']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['absNetProfit']) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-end fw-bold"><span>{{ __("Total") }} </span></td>

    <td class="fw-bold">:
        @if ($liabilityDetails['closingBalanceSide'] == 'dr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($liabilityDetails['closingBalance']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($liabilityDetails['closingBalance']) }}</span>
        @endif
    </td>
</tr>
