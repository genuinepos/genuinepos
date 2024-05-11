<table class="w-100 selectable">
    <thead>
        <tr>
            <th rowspan="2" class="header_text text-center" style="border-top:1px solid black;">{{ __("Particulars") }}</th>
            <th colspan="2" class="header_text text-center" style="border:1px solid black;">{{ __("Opening Balance") }}</th>
            <th colspan="2" class="header_text text-center" style="border:1px solid black;">{{ __("Closing Balance") }}</th>
        </tr>

        <tr>
            <th class="header_text text-end pe-1" style="border-left:1px solid black;border-right:1px solid black;">{{ __("Debit") }}</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __("Credit") }}</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __("Debit") }}</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __("Credit") }}</th>
        </tr>
    </thead>
    @php
        // $totalDebitOpeningBalance = $openingStock;
        $totalDebitOpeningBalance = 0;
        $totalCreditOpeningBalance = 0;
        // $totalDebitClosingBalance = $openingStock;
        $totalDebitClosingBalance = 0;
        $totalCreditClosingBalance = 0;
    @endphp
    <tbody class="trial_balance_main_table_body">
        {{-- @if ($openingStock > 0)
            <tr class="opening_stock">
                <td class="text-start fw-bold">@lang('menu.opening_stock')</td>
                <td class="text-end debit_amount fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                <td class="text-end closing_balance fw-bold"></td>
                <td class="text-end closing_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                <td class="text-end closing_balance fw-bold"></td>
            </tr>
        @endif --}}

        @foreach ($accountGroups as $key => $mainGroup)

            @if ($key != 0)

                @if ($mainGroup['debit_closing_balance'] > 0 || $mainGroup['credit_closing_balance'] > 0)

                    @php
                        $totalDebitOpeningBalance +=  $mainGroup['debit_opening_balance'];
                        $totalCreditOpeningBalance += $mainGroup['credit_opening_balance'];
                        $totalDebitClosingBalance += $mainGroup['debit_closing_balance'];
                        $totalCreditClosingBalance += $mainGroup['credit_closing_balance'];
                    @endphp

                    <tr class="account_group_list">
                        {{-- <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td> --}}
                        <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td>

                        <td class="text-end fw-bold" style="{{ ($mainGroup['debit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">
                            {{ ($mainGroup['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['debit_opening_balance']) : '' ) }}
                        </td>

                        <td class="text-end fw-bold" style="{{ ($mainGroup['credit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">
                            {{ ($mainGroup['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['credit_opening_balance']) : '' ) }}
                        </td>

                        <td class="text-end fw-bold" style="{{ ($mainGroup['debit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">{{ $mainGroup['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['debit_closing_balance']) : '' }}</td>

                        <td class="text-end fw-bold" style="{{ ($mainGroup['credit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">{{ $mainGroup['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['credit_closing_balance']) : '' }}</td>
                    </tr>

                    @if ($formatOfReport == 'detailed')

                        @if (count($mainGroup['groups']) > 0)

                            @foreach ($mainGroup['groups'] as $group)

                                @if ($group['debit_closing_balance'] > 0 || $group['credit_closing_balance'] > 0)

                                    <tr class="account_group_list">
                                        <td class="text-start ps-1">{{ $group['group_name'] }}</td>

                                        <td class="text-end">
                                            {{ ($group['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['debit_opening_balance']) : '' ) }}
                                        </td>

                                        <td class="text-end">
                                            {{ ($group['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['credit_opening_balance']) : '' ) }}
                                        </td>

                                        <td class="text-end">
                                            {{ $group['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($group['debit_closing_balance']) : '' }}
                                        </td>

                                        <td class="text-end">
                                            {{ $group['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($group['credit_closing_balance']) : '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif

                     @if ($formatOfReport == 'detailed')

                        @if (count($mainGroup['accounts']) > 0)

                            @foreach ($mainGroup['accounts'] as $account)

                                @if ($account['debit_closing_balance'] > 0 || $account['credit_closing_balance'] > 0)
                                    <tr class="account_group_list">
                                        <td class="text-start ps-1">{{ $account['account_name'] }}</td>

                                        <td class="text-end">
                                            {{ ($account['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['debit_opening_balance']) : '' ) }}
                                        </td>

                                        <td class="text-end">
                                            {{ ($account['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['credit_opening_balance']) : '' ) }}
                                        </td>

                                        <td class="text-end">
                                            {{ $account['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($account['debit_closing_balance']) : '' }}
                                        </td>

                                        <td class="text-end">
                                            {{ $account['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($account['credit_closing_balance']) : '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endif
            @endif
        @endforeach

        @php
            $differenceInOpeningBalance = 0;
            $differenceInOpeningBalanceSide = 'dr';
            if ($totalDebitClosingBalance > $totalCreditClosingBalance) {

                $differenceInOpeningBalance = $totalDebitClosingBalance - $totalCreditClosingBalance;
                $differenceInOpeningBalanceSide = 'dr';
            }elseif($totalCreditClosingBalance > $totalDebitClosingBalance) {

                $differenceInOpeningBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                $differenceInOpeningBalanceSide = 'cr';
            }

            $totalDebitOpeningBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
            $totalCreditOpeningBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
            $totalDebitClosingBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
            $totalCreditClosingBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
        @endphp
        <tr class="difference_in_opening_balance_area">
            <td class="text-start fw-bold" style="text-align: right!important;">{{ __("Difference In Opening Balance") }} :</td>
            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
        </tr>
    </tbody>

    <tfoot class="net_total_balance_footer">
        <td class="text-end footer_total fw-bold">{{ __("Total") }} :</td>
        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitOpeningBalance) }}</td>
        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditOpeningBalance) }}</td>
        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitClosingBalance) }}</td>
        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditClosingBalance) }}</td>
    </tfoot>
</table>
