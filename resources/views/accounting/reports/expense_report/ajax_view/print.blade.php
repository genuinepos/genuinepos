@php
    $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Expense Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($dateFormat, strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($dateFormat, strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-3">
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
            <p><strong>{{ __('Shop/Business') }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }} </p>
        </div>

        <div class="col-3">
            <p><strong>{{ __('Chain Shop') }} : </strong> {{ $filteredChildBranchName }} </p>
        </div>

        <div class="col-3">
            <p><strong>{{ __('Group') }} : </strong> {{ $filteredExpenseGroupName }} </p>
        </div>

        <div class="col-3">
            <p><strong>{{ __('Ledger') }} : </strong> {{ $filteredExpenseAccountName }} </p>
        </div>
    </div>


    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th>{{ __('Expense Ledger/Account') }}</th>
                        <th>{{ __('Shop/Business') }}</th>
                        <th>{{ __('Voucher Type') }}</th>
                        <th>{{ __('Voucher No') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @php
                        $previousDate = '';
                        $totalAmount = 0;
                        $dateTotalAmount = 0;
                        $isSameDate = true;
                        $lastDate = null;
                        $lastDateTotalAmount = 0;
                    @endphp
                    @foreach ($expenses as $ex)
                        @php
                            $totalAmount += $ex->amount;
                            $date = date($dateFormat, strtotime($ex->date));
                            $isSameDate = null != $lastDate && $lastDate == $ex->date ? true : false;
                            $lastDate = $ex->date;
                        @endphp

                        @if ($isSameDate == true)
                            @php
                                $dateTotalAmount += $ex->amount;
                            @endphp
                        @else
                            @if ($dateTotalAmount != 0)
                                <tr>
                                    <td colspan="4" class="fw-bold text-end">{{ __('Total') }} : </td>
                                    <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($dateTotalAmount) }}</td>
                                </tr>
                            @endif

                            @php $dateTotalAmount = 0; @endphp
                        @endif

                        @if ($previousDate != $ex->date)
                            @php
                                $previousDate = $ex->date;
                                $dateTotalAmount += $ex->amount;
                            @endphp

                            <tr>
                                <td class="text-start text-uppercase fw-bold" colspan="4">{{ $date }} </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">
                                {{ $ex->account_name }}
                            </td>

                            <td class="text-start">
                                @if ($ex->branch_id)
                                    @if ($ex->parent_branch_name)
                                        {{ $ex->parent_branch_name . '(' . $ex->area_name . ')' }}
                                    @else
                                        {{ $ex->branch_name . '(' . $ex->area_name . ')' }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </td>

                            <td class="text-start">
                                @php
                                    $type = $accountLedgerService->voucherType($ex->voucher_type);
                                @endphp
                                {!! $ex->voucher_type != 0 ? $type['name'] : '' !!}
                            </td>

                            <td class="text-start fw-bold">{!! $ex->{$type['voucher_no']} !!}</td>

                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ex->amount) }}</td>
                        </tr>

                        @php
                            $__veryLastDate = $veryLastDate;
                            $currentDate = $ex->date;
                            if ($currentDate == $__veryLastDate) {
                                $lastDateTotalAmount += $ex->amount;
                            }
                        @endphp

                        @if ($loop->index == $lastRow)
                            <tr>
                                <td colspan="4" class="fw-bold text-end">{{ __('Total') }} : </td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastDateTotalAmount) }}</td>
                            </tr>
                        @endif
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
                        <th class="text-end">{{ __('Total Expense') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalAmount) }}
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
    $fileBranchName = $filteredBranchName ? 'Shop/Business:' . $filteredBranchName : $ownOrParentbranchName;
    $fileChildBranchName = $filteredChildBranchName ? '__Child Shop:' . $filteredChildBranchName : '';
    $dateRange = $fromDate && $toDate ? '__' . $fromDate . '_To_' . $toDate : '';
    $filename = __('Expense Report') . $dateRange . '__' . $fileBranchName . $fileChildBranchName;
@endphp
<span id="title" class="d-none">{{ $filename }}</span>
<!-- Stock Issue print templete end-->
