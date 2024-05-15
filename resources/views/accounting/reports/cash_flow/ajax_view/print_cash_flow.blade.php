<style>
    @media print {
        table {
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

    td.outflow_area {
        border: 1px solid #000;
    }

    .net_total_balance_footer tr {
        border: 1px solid;
        line-height: 16px;
    }

    .net_credit_total {
        border: 1px solid #000;
    }

    td.inflow_area {
        border-left: 1px solid #000;
        line-height: 17px;
        padding-right: 6px;
    }

    td.outflow_area {
        line-height: 17px;
        padding-right: 6px;
    }

    /* font-family: sans-serif; */
    td.first_td {
        width: 72%;
    }

    .header_text {
        letter-spacing: 3px;
        border: 1px solid;
        background-color: #fff !important;
        color: #000 !important
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
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 40px; width:100px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Cash Flow') }}</strong></h6>
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
            <p><span class="fw-bold">{{ __('Shop/Business') }} : </span> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-6">
            <p><span class="fw-bold">{{ __('Child Shop') }} : </span> {{ $filteredChildBranchName }} </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <table class="w-100">
                <thead>
                    <tr>
                        <th class="header_text ps-1 text-center">{{ __('INFLOW') }}</th>
                        <th class="header_text ps-1 text-center" style="border-left: 1px solid black;">{{ __('OUTFLOW') }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="inflow_area" style="width: 50%;">
                            @if ($capitalAccountCashFlows->cash_in > 0)
                                <table class="capital_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $capitalAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($capitalAccountCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($capitalAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($branchAndDivisionCashFlows->cash_in > 0)
                                <table class="branch_and_division_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $branchAndDivisionCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($branchAndDivisionCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($suspenseAccountCashFlows->cash_in > 0)
                                <table class="suspense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $suspenseAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($suspenseAccountCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($suspenseAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($currentLiabilitiesCashFlows->cash_in > 0)
                                <table class="current_liabilities_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $currentLiabilitiesCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($loanLiabilitiesCashFlows->cash_in > 0)
                                <table class="loan_liabilities_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $loanLiabilitiesCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($currentAssetsCashFlows->cash_in > 0)
                                <table class="current_assets_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $currentAssetsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($currentAssetsCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($currentAssetsCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($fixedAssetsCashFlows->cash_in > 0)
                                <table class="fixed_assets_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $fixedAssetsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($fixedAssetsCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($fixedAssetsCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($investmentsCashFlows->cash_in > 0)
                                <table class="investments_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $investmentsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($investmentsCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($investmentsCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</a></td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($directExpenseCashFlows->cash_in > 0)
                                <table class="direct_expense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $directExpenseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($directExpenseCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($directExpenseCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($indirectExpenseCashFlows->cash_in > 0)
                                <table class="indirect_expense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $indirectExpenseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($indirectExpenseCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($indirectExpenseCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($purchaseCashFlows->cash_in > 0)
                                <table class="indirect_expense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $purchaseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($purchaseCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($purchaseCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($directIncomeCashFlows->cash_in > 0)
                                <table class="direct_income_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $directIncomeCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($directIncomeCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($directIncomeCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($indirectIncomeCashFlows->cash_in > 0)
                                <table class="indirect_income_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $indirectIncomeCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($indirectIncomeCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($indirectIncomeCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($salesAccountCashFlows->cash_in > 0)
                                <table class="sales_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $salesAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($salesAccountCashFlows->groups as $group)
                                                        @if ($group->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($salesAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_in > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_in) }}</strong></td>
                                    </tr>
                                </table>
                            @endif
                        </td>

                        <td class="outflow_area">
                            @if ($capitalAccountCashFlows->cash_out > 0)
                                <table class="capital_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $capitalAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($capitalAccountCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($capitalAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($branchAndDivisionCashFlows->cash_out > 0)
                                <table class="branch_and_division_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $branchAndDivisionCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($branchAndDivisionCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($suspenseAccountCashFlows->cash_out > 0)
                                <table class="suspense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $suspenseAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($suspenseAccountCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($suspenseAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>

                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($currentLiabilitiesCashFlows->cash_out > 0)
                                <table class="current_liabilities_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $currentLiabilitiesCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($loanLiabilitiesCashFlows->cash_out > 0)
                                <table class="loan_liabilities_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $loanLiabilitiesCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($currentAssetsCashFlows->cash_out > 0)
                                <table class="current_assets_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $currentAssetsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($currentAssetsCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($currentAssetsCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($fixedAssetsCashFlows->cash_out > 0)
                                <table class="fixed_assets_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $fixedAssetsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($fixedAssetsCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($fixedAssetsCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($investmentsCashFlows->cash_out > 0)
                                <table class="investments_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $investmentsCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($investmentsCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($investmentsCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($directExpenseCashFlows->cash_out > 0)
                                <table class="direct_expense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $directExpenseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($directExpenseCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($directExpenseCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($indirectExpenseCashFlows->cash_out > 0)
                                <table class="indirect_expense_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $indirectExpenseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($indirectExpenseCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($indirectExpenseCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($purchaseCashFlows->cash_out > 0)
                                <table class="purchase_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $purchaseCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($purchaseCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($purchaseCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($directIncomeCashFlows->cash_out > 0)
                                <table class="direct_income_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $directIncomeCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($directIncomeCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($directIncomeCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($indirectIncomeCashFlows->cash_out > 0)
                                <table class="indirect_income_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $indirectIncomeCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($indirectIncomeCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($indirectIncomeCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif

                            @if ($salesAccountCashFlows->cash_out > 0)
                                <table class="indirect_income_account_group_table w-100 mt-1">
                                    <tr>
                                        <td class="first_td">
                                            <strong class="ps-2">{{ $salesAccountCashFlows->main_group_name }}</strong>
                                            @if ($formatOfReport == 'detailed')
                                                <table class="group_account_table ms-2">
                                                    @foreach ($salesAccountCashFlows->groups as $group)
                                                        @if ($group->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">>{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-{{ __('Group') }}</span>)</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                    @foreach ($salesAccountCashFlows->accounts as $account)
                                                        @if ($account->cash_out > 0)
                                                            <tr>
                                                                <td class="group_account_name ps-1">{{ $account->account_name }} ({{ __('Ledger') }})</td>
                                                                <td class="group_account_balance text-end">
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_out) }}</strong></td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                </tbody>

                <tfoot class="net_total_balance_footer">
                    <tr>
                        <th class="text-end fw-bold net_debit_total pe-1">{{ __('Total') }} : {{ \App\Utils\Converter::format_in_bdt($totalIn) }}</th>
                        <th class="text-end fw-bold net_credit_total pe-1">{{ __('Total') }} : {{ \App\Utils\Converter::format_in_bdt($totalOut) }}</th>
                    </tr>

                    <tr>
                        <th class="text-end fw-bold net_debit_total pe-1">{{ __('Net Inflow') }} : </th>
                        <th class="text-start fw-bold net_credit_total ps-1">
                            {{ $balanceSide == 'out' ? '(-)' : '' }}
                            {{ \App\Utils\Converter::format_in_bdt($balance) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small>{{ __('Powered By') }} <span class="fw-bold">{{ __('Speed Digit Software Solution') }}.</span></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>

@php
    $fileBranchName = $filteredBranchName ? 'Shop/Business: ' . $filteredBranchName : $ownOrParentbranchName;
    $fileChildBranchName = $filteredChildBranchName ? '__Child Shop: ' . $filteredChildBranchName : '';
    $dateRange = $fromDate && $toDate ? '__' . $fromDate . '_To_' . $toDate : '';
    $filename = __('Cash Flow') . $dateRange . '__' . $fileBranchName . $fileChildBranchName;
@endphp
<span id="title" class="d-none">{{ $filename }}</span>
<!-- Stock Issue print templete end-->
