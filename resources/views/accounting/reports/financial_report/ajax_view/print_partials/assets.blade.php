<tr>
    <th class="text-start">
        <span>{{ __('Assets') }} : </span>
    </th>
    <th class="text-start"></th>
</tr>

<tr>
    <td class="text-start ps-2 fw-bold" style="padding-left: 20px!important;">
        <span>{{ __('Current Assets') }} : </span>
    </td>
    <td class="text-start"></td>
</tr>

@foreach ($assetDetails['currentAsset']->groups as $group)
    <tr>
        <td style="padding-left: 40px!important;">{{ $group->group_name }}</td>
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
    <td class="text-start fw-bold" style="padding-left: 20px!important;">{{ $assetDetails['fixedAsset']->main_group_name }} :</td>
    <td class="text-start">:
        @if ($assetDetails['fixedAsset']->closing_balance_side == 'cr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($assetDetails['fixedAsset']->closing_balance) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($assetDetails['fixedAsset']->closing_balance) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-start fw-bold" style="padding-left: 20px!important;">{{ $assetDetails['investments']->main_group_name }} :</td>
    <td class="text-start">:
        @if ($assetDetails['investments']->closing_balance_side == 'cr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($assetDetails['investments']->closing_balance) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($assetDetails['investments']->closing_balance) }}</span>
        @endif
    </td>
</tr>

<tr>
    <td class="text-end fw-bold"><span>{{ __("Total") }} </span></td>

    <td class="fw-bold">:
        @if ($assetDetails['closingBalanceSide'] == 'cr')
            <span>(-) {{ \App\Utils\Converter::format_in_bdt($assetDetails['closingBalance']) }}</span>
        @else
            <span>{{ \App\Utils\Converter::format_in_bdt($assetDetails['closingBalance']) }}</span>
        @endif
    </td>
</tr>
