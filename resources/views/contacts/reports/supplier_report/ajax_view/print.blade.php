@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
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
        margin-left: 5px;
        margin-right: 5px;
    }

    div#footer {
        position: fixed;
        bottom: 20px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    .print_table th {
        font-size: 11px !important;
        font-weight: 550 !important;
        line-height: 12px !important
    }

    .print_table tr td {
        color: black;
        font-size: 10px !important;
        line-height: 12px !important
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
</style>

<div class="print_area">
    <div class="row" style="border-bottom: 1px solid black;">
        <div class="col-4 mb-1">
            @if (auth()->user()?->branch)
                @if (auth()->user()?->branch?->parent_branch_id)

                    @if (auth()->user()?->branch?->parentBranch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 40px; width:100px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                @endif
            @endif
        </div>

        <div class="col-8 text-end">

            <p style="text-transform: uppercase;" class="p-0 m-0">
                <strong>
                    @if (auth()->user()?->branch)
                        @if (auth()->user()?->branch?->parent_branch_id)
                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else
                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business_or_shop__business_name'] }}
                    @endif
                </strong>
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
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business_or_shop__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business_or_shop__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Supplier Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            @php
                $ownOrParentbranchName = $generalSettings['business_or_shop__business_name'];
                if (auth()->user()?->branch) {
                    if (auth()->user()?->branch->parentBranch) {
                        $ownOrParentbranchName = auth()->user()?->branch->parentBranch?->name;
                    } else {
                        $ownOrParentbranchName = auth()->user()?->branch?->name;
                    }
                }
            @endphp
            <p><strong>{{ __('Shop/Business') }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-6">
            <p><strong>{{ __('Customer') }} : </strong> {{ $filteredSupplierName }} </p>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th>{{ __('Opening Balance') }}</th>
                        <th>{{ __('Total Purchase') }}</th>
                        <th>{{ __('Total Sale') }}</th>
                        <th>{{ __('Total Return') }}</th>
                        <th>{{ __('Total Paid') }}</th>
                        <th>{{ __('Total Received') }}</th>
                        <th>{{ __('Curr. Balance') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $allTotalOpeningBalance = 0;
                        $allTotalSale = 0;
                        $allTotalPurchase = 0;
                        $allTotalReturn = 0;
                        $allTotalReceived = 0;
                        $allTotalPaid = 0;
                        $allTotalCurrentBalance = 0;
                    @endphp
                    @foreach ($suppliers as $row)
                        <tr>
                            <td class="text-start text-uppercase fw-bold" colspan="8">{{ $row->name . ' / ' . $row->phone }} </td>
                        </tr>

                        <tr>
                            <td class="text-start fw-bold">
                                @php
                                    $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                                    $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                                    $openingBalanceInFlatAmount = 0;
                                    if ($row->default_balance_type == 'dr') {
                                        $openingBalanceInFlatAmount = $openingBalanceDebit - $openingBalanceCredit;
                                    } elseif ($row->default_balance_type == 'cr') {
                                        $openingBalanceInFlatAmount = $openingBalanceCredit - $openingBalanceDebit;
                                    }

                                    $allTotalOpeningBalance += $openingBalanceInFlatAmount;

                                    $__openingBalanceInFlatAmount = $openingBalanceInFlatAmount < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($openingBalanceInFlatAmount)) . ')' : \App\Utils\Converter::format_in_bdt($openingBalanceInFlatAmount);
                                @endphp

                                {{ $__openingBalanceInFlatAmount }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $allTotalPurchase = $row->total_purchase;
                                @endphp

                                {{ \App\Utils\Converter::format_in_bdt($row->total_purchase) }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $allTotalSale += $row->total_sale;
                                @endphp

                                {{ \App\Utils\Converter::format_in_bdt($row->total_sale) }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $totalSalesReturn = $row->total_sales_return;
                                    $totalPurchaseReturn = $row->total_purchase_return;

                                    $totalReturn = 0;
                                    if ($row->default_balance_type == 'dr') {
                                        $totalReturn = $totalSalesReturn - $totalPurchaseReturn;
                                    } elseif ($row->default_balance_type == 'cr') {
                                        $totalReturn = $totalPurchaseReturn - $totalSalesReturn;
                                    }

                                    $allTotalReturn += $totalReturn;

                                    $__totalReturn = $totalReturn < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($totalReturn)) . ')' : \App\Utils\Converter::format_in_bdt($totalReturn);
                                @endphp

                                {{ $__totalReturn }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $allTotalPaid += $row->total_paid;
                                @endphp
                                {{ \App\Utils\Converter::format_in_bdt($row->total_paid) }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $allTotalReceived += $row->total_received;
                                @endphp
                                {{ \App\Utils\Converter::format_in_bdt($row->total_received) }}
                            </td>

                            <td class="text-start fw-bold">
                                @php
                                    $openingBalanceDebit = $row->opening_total_debit;
                                    $openingBalanceCredit = $row->opening_total_credit;

                                    $currTotalDebit = $row->curr_total_debit;
                                    $currTotalCredit = $row->curr_total_credit;

                                    $currOpeningBalance = 0;
                                    $currOpeningBalanceSide = $row->default_balance_type;
                                    if ($openingBalanceDebit > $openingBalanceCredit) {
                                        $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                                        $currOpeningBalanceSide = 'dr';
                                    } elseif ($openingBalanceCredit > $openingBalanceDebit) {
                                        $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                                        $currOpeningBalanceSide = 'cr';
                                    }

                                    $currTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                                    $currTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                                    $closingBalanceInFlatAmount = 0;
                                    if ($row->default_balance_type == 'dr') {
                                        $closingBalanceInFlatAmount = $currTotalDebit - $currTotalCredit;
                                    } elseif ($row->default_balance_type == 'cr') {
                                        $closingBalanceInFlatAmount = $currTotalCredit - $currTotalDebit;
                                    }

                                    $allTotalCurrentBalance += $closingBalanceInFlatAmount;

                                    $__closingBalanceInFlatAmount = $closingBalanceInFlatAmount < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($closingBalanceInFlatAmount)) . ')' : \App\Utils\Converter::format_in_bdt($closingBalanceInFlatAmount);
                                @endphp

                                {{ $__closingBalanceInFlatAmount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
    <div class="row">
        {{-- <div class="col-6"></div> --}}
        <div class="col-6 offset-6">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-end">{{ __('Total Opening Balance') }} : </th>
                        <td class="text-end">
                            @php
                                $__allTotalOpeningBalance = $allTotalOpeningBalance < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($allTotalOpeningBalance)) . ')' : \App\Utils\Converter::format_in_bdt($allTotalOpeningBalance);
                            @endphp
                            {{ $__allTotalOpeningBalance }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Purchase') }} : </th>
                        <td class="text-end">
                            {{ \App\Utils\Converter::format_in_bdt($allTotalPurchase) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Sale') }} : </th>
                        <td class="text-end">
                            {{ \App\Utils\Converter::format_in_bdt($allTotalSale) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Return') }} : </th>
                        <td class="text-end">
                            @php
                                $__allTotalReturn = $allTotalReturn < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($allTotalReturn)) . ')' : \App\Utils\Converter::format_in_bdt($allTotalReturn);
                            @endphp
                            {{ $__allTotalReturn }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Paid') }} : </th>
                        <td class="text-end">
                            {{ \App\Utils\Converter::format_in_bdt($allTotalPaid) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Received') }} : </th>
                        <td class="text-end">
                            {{ \App\Utils\Converter::format_in_bdt($allTotalReceived) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Currant Balance') }} : </th>
                        <td class="text-end">
                            @php
                                $__allTotalCurrentBalance = $allTotalCurrentBalance < 0 ? '(' . \App\Utils\Converter::format_in_bdt(abs($allTotalCurrentBalance)) . ')' : \App\Utils\Converter::format_in_bdt($allTotalCurrentBalance);
                            @endphp
                            {{ $__allTotalCurrentBalance }}
                        </td>
                    </tr>
                </thead>
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
                    <small>{{ __('Powered By') }} <strong>{{ __('Speed Digit Software Solution') }}.</strong></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>

@php
    $fileBranchName = $filteredBranchName ? 'Shop/Business:' . $filteredBranchName : $ownOrParentbranchName;
    $fileSupplierName = $filteredSupplierName ? '__' . $$filteredSupplierName : '';

    $filename = __('Supplier Report') . '__' . $fileBranchName . $fileSupplierName;
@endphp
<span id="title" class="d-none">{{ $filename }}</span>
<!-- Stock Issue print templete end-->
