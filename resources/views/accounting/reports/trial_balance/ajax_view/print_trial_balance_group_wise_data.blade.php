<style>
    @media print {
        table {
            font-family: Arial, Helvetica, sans-serif;
            page-break-after: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto, font-size:9px !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 20px;
        margin-right: 20px;
    }

    div#footer {
        position: fixed;
        bottom: 22px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    .print_area {
        font-family: Arial, Helvetica, sans-serif;
    }

    .print_area h6 {
        font-size: 14px !important;
    }

    .print_area p {
        font-size: 11px !important;
    }

    .print_area small {
        font-size: 8px !important;
    }

    .net_total_balance_footer tr {
        border-top: 1px solid;
        border-bottom: 1px solid;
        line-height: 16px;
    }

    td.trial_balance_area {
        line-height: 17px !important;
    }

    .header_text {
        letter-spacing: 3px;
        border-bottom: 1px solid;
        background-color: #fff !important;
        color: #000 !important
    }

    tr.account_list td {
        border-bottom: 1px solid lightgray;
    }

    tr.account_group_list td {
        border-bottom: 1px solid lightgray;
    }

    .trial_balance_area tbody tr td {
        line-height: 16px;
    }

    .footer_total {
        font-size: 13px !important;
    }
</style>

@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                @endif
            @endif
        </div>

        <div class="col-8 text-end">
            <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                @if (auth()->user()?->branch)
                    @if (auth()->user()?->branch?->parent_branch_id)
                        {{ auth()->user()?->branch?->parentBranch?->name }}
                    @else
                        {{ auth()->user()?->branch?->name }}
                    @endif
                @else
                    {{ $generalSettings['business_or_shop__business_name'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->address . ', ' . auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business_or_shop__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <span class="fw-bold">{{ __('Email') }} : </span> {{ auth()->user()?->branch?->email }},
                    <span class="fw-bold">{{ __('Phone') }} : </span> {{ auth()->user()?->branch?->phone }}
                @else
                    <span class="fw-bold">{{ __('Email') }} : </span> {{ $generalSettings['business_or_shop__email'] }},
                    <span class="fw-bold">{{ __('Phone') }} : </span> {{ $generalSettings['business_or_shop__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Trial Balance') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <span class="fw-bold">{{ __('From') }} :</span>
                    {{ date($dateFormat, strtotime($fromDate)) }}
                    <span class="fw-bold">{{ __('To') }} : </span> {{ date($dateFormat, strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business_or_shop__business_name'];
                if (auth()->user()?->branch) {
                    if (auth()->user()?->branch->parentBranch) {
                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name . '(' . auth()->user()?->branch->parentBranch?->area_name . ')';
                    } else {
                        $ownOrParentbranchName = auth()->user()?->branch?->name . '(' . auth()->user()?->branch?->area_name . ')';
                    }
                }
            @endphp
            <p><span class="fw-bold">{{ location_label() }} : </span> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-6">
            <p><span class="fw-bold">{{ __('Chain Shop') }} : </span> {{ $filteredChildBranchName }} </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="trial_balance_area">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th rowspan="2" class="header_text text-center" style="border-top:1px solid black;">{{ __('Particulars') }}</th>
                            <th colspan="2" class="header_text text-center" style="border:1px solid black;">{{ __('Opening Balance') }}</th>
                            <th colspan="2" class="header_text text-center" style="border:1px solid black;">{{ __('Closing Balance') }}</th>
                        </tr>

                        <tr>
                            <th class="header_text text-end pe-1" style="border-left:1px solid black;border-right:1px solid black;">{{ __('Debit') }}</th>
                            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __('Credit') }}</th>
                            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __('Debit') }}</th>
                            <th class="header_text text-end pe-1" style="border-right:1px solid black;">{{ __('Credit') }}</th>
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
                                        $totalDebitOpeningBalance += $mainGroup['debit_opening_balance'];
                                        $totalCreditOpeningBalance += $mainGroup['credit_opening_balance'];
                                        $totalDebitClosingBalance += $mainGroup['debit_closing_balance'];
                                        $totalCreditClosingBalance += $mainGroup['credit_closing_balance'];
                                    @endphp

                                    <tr class="account_group_list">
                                        {{-- <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td> --}}
                                        <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td>

                                        <td class="text-end fw-bold" style="{{ $mainGroup['debit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' }}">
                                            {{ $mainGroup['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['debit_opening_balance']) : '' }}
                                        </td>

                                        <td class="text-end fw-bold" style="{{ $mainGroup['credit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' }}">
                                            {{ $mainGroup['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['credit_opening_balance']) : '' }}
                                        </td>

                                        <td class="text-end fw-bold" style="{{ $mainGroup['debit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' }}">{{ $mainGroup['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['debit_closing_balance']) : '' }}</td>

                                        <td class="text-end fw-bold" style="{{ $mainGroup['credit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' }}">{{ $mainGroup['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['credit_closing_balance']) : '' }}</td>
                                    </tr>

                                    @if ($formatOfReport == 'detailed')
                                        @if (count($mainGroup['groups']) > 0)
                                            @foreach ($mainGroup['groups'] as $group)
                                                @if ($group['debit_closing_balance'] > 0 || $group['credit_closing_balance'] > 0)
                                                    <tr class="account_group_list">
                                                        <td class="text-start ps-1">{{ $group['group_name'] }}</td>

                                                        <td class="text-end">
                                                            {{ $group['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['debit_opening_balance']) : '' }}
                                                        </td>

                                                        <td class="text-end">
                                                            {{ $group['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['credit_opening_balance']) : '' }}
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
                                                            {{ $account['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['debit_opening_balance']) : '' }}
                                                        </td>

                                                        <td class="text-end">
                                                            {{ $account['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['credit_opening_balance']) : '' }}
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
                            } elseif ($totalCreditClosingBalance > $totalDebitClosingBalance) {
                                $differenceInOpeningBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                                $differenceInOpeningBalanceSide = 'cr';
                            }

                            $totalDebitOpeningBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
                            $totalCreditOpeningBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
                            $totalDebitClosingBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
                            $totalCreditClosingBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
                        @endphp
                        <tr class="difference_in_opening_balance_area">
                            <td class="text-start fw-bold" style="text-align: right!important;">{{ __('Difference In Opening Balance') }} :</td>
                            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                        </tr>
                    </tbody>

                    <tfoot class="net_total_balance_footer">
                        <td class="text-end footer_total fw-bold">{{ __('Total') }} :</td>
                        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitOpeningBalance) }}</td>
                        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditOpeningBalance) }}</td>
                        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitClosingBalance) }}</td>
                        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditClosingBalance) }}</td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('speeddigit.show_app_info_in_print') == true)
                    <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>

@php
    $fileBranchName = $filteredBranchName ? location_label() . ': ' . $filteredBranchName : $ownOrParentbranchName;
    $fileChildBranchName = $filteredChildBranchName ? '__Child Store: ' . $filteredChildBranchName : '';
    $dateRange = $fromDate && $toDate ? '__' . $fromDate . '_To_' . $toDate : '';
    $filename = __('Trial Balance') . $dateRange . '__' . $fileBranchName . $fileChildBranchName;
@endphp
<span id="title" class="d-none">{{ $filename }}</span>
<!-- Stock Issue print templete end-->
