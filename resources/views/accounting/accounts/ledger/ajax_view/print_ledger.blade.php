<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto, font-size:9px !important;
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
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

                    @if (auth()->user()?->branch?->parentBranch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo != 'default.png')
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else
                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
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
                        {{ $generalSettings['business__business_name'] }}
                    @endif
                </strong>
            </p>

            <p>
                @if (auth()->user()?->branch)
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </p>

            <p>
                @if (auth()->user()?->branch)
                    <strong>{{ __('Email') }} : </strong> {{ auth()->user()?->branch?->email }},
                    <strong>{{ __('Phone') }} : </strong> {{ auth()->user()?->branch?->phone }}
                @else
                    <strong>{{ __('Email') }} : </strong> {{ $generalSettings['business__email'] }},
                    <strong>{{ __('Phone') }} : </strong> {{ $generalSettings['business__phone'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 text-center">
            <h6 style="text-transform:uppercase;"><strong>{{ __('Account Ledger') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($fromDate && $toDate)
                <p>
                    <strong>{{ __('From') }} :</strong>
                    {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                    <strong>{{ __('To') }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
                </p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <table>
                {{-- <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Group') }}</th>
                    <td><strong>:</strong> {{ $account?->group?->name }}</td>
                </tr> --}}

                @if ($account->bank_name)
                    <tr style="line-height: 18px;">
                        <td class="fw-bold">{{ __('Bank') }}</th>
                        <td><strong>:</strong> {{ $account->bank_name }}</td>
                    </tr>
                @endif

                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('A/c Name') }}</td>
                    <td><strong>:</strong> {{ $account->name }} {{ $account->account_number ? ' / ' . $account->account_number : '' }}</td>
                </tr>

                <tr style="line-height: 18px;">
                    <td class="fw-bold">{{ __('Phone') }}</td>
                    <td><strong>:</strong> {{ $account->phone }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            @php
                $ownOrParentbranchName = $generalSettings['business__business_name'];
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
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);
        $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
        $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: $request->from_date, toDate: $request->to_date, branchId: $request->branch_id);
    @endphp

    <div class="row mt-1">
        <div class="col-12 print_table_area">
            <table class="table report-table table-sm print_table">
                <thead>
                    <tr>
                        <th class="text-start">{{ __('Date') }}</th>
                        <th class="text-start">{{ __('Particulars') }}</th>
                        <th class="text-start">{{ __('Voucher Type') }}</th>
                        <th class="text-start">{{ __('Voucher No') }}</th>
                        <th class="text-end">{{ __('Debit') }}</th>
                        <th class="text-end">{{ __('Credit') }}</th>
                        <th class="text-end">{{ __('Running Balance') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $previousDate = '';
                        $isEmptyDate = 0;
                    @endphp
                    @foreach ($entries as $row)
                        <tr class="main_tr">
                            <td class="text-start fw-bold main_td" style="border-bottom: 0px solid black!important;">
                                @php
                                    $date = $row->date ? date($__date_format, strtotime($row->date)) : '';
                                @endphp

                                @if ($previousDate != $date)
                                    @php
                                        $previousDate = $date;
                                        $isEmptyDate = 0;
                                    @endphp
                                    {{ $date }}
                                @endif
                            </td>

                            <td class="text-start main_td">
                                @php
                                    $voucherType = $row->voucher_type;
                                    $ledgerParticulars = new \App\Services\Accounts\AccountLedgerPrintParticularService();
                                @endphp
                                {!! $ledgerParticulars->particulars($request, $voucherType, $row) !!}
                            </td>

                            <td class="text-start main_td">
                                @php
                                    $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                                    $type = $accountLedgerService->voucherType($row->voucher_type);
                                @endphp
                                {!! $row->voucher_type != 0 ? '<strong>' . $type['name'] . '</strong>' : '' !!}
                            </td>

                            <td class="text-start main_td">{!! $row->{$type['voucher_no']} !!}</td>
                            <td class="text-end fw-bold main_td">
                                {{ $row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '' }}
                            </td>

                            <td class="text-end fw-bold main_td">
                                {{ $row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '' }}
                            </td>

                            <td class="text-end fw-bold main_td">
                                {{ $row->running_balance > 0 ? \App\Utils\Converter::format_in_bdt(abs($row->running_balance)) . $row->balance_type : '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center fw-bold">{{ __('Account Summary') }}</th>
                    </tr>

                    <tr>
                        <th class="text-end"></th>
                        <th class="text-end fw-bold">{{ __('Debit') }}</th>
                        <th class="text-end fw-bold">{{ __('Credit') }}</th>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Opening Balance') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Current Total') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_debit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_debit']) : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_credit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_credit']) : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Closing Balance') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="footer">
        <div class="row">
            <div class="col-4 text-start">
                <small>{{ __('Print Date') }} : {{ date($__date_format) }}</small>
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
