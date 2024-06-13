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
        bottom: 24px;
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
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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
                    {{ auth()->user()?->branch?->city . ', ' . auth()->user()?->branch?->state . ', ' . auth()->user()?->branch?->zip_code . ', ' . auth()->user()?->branch?->country }}
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Payroll Report') }}</strong></h6>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-12 text-center">
            @if ($month && $year)
                <p>
                    <strong>{{ __('Month') }} :</strong> {{ $month . ' - ' . $year }}
                </p>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-4">
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

        <div class="col-4">
            <p><strong>{{ __('Department') }} : </strong> {{ $filteredDepartmentName }} </p>
        </div>

        <div class="col-4">
            <p><strong>{{ __('Employee') }} : </strong> {{ $filteredUserName }} </p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

        $totalPayroll = 0;
        $totalPaid = 0;
        $totalDue = 0;
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Payroll Voucher') }}</th>
                        <th>{{ __('Shop/Business') }}</th>
                        <th>{{ __('Pay Status') }}</th>
                        <th>{{ __('Duration Unit') }}</th>
                        <th class="text-end">{{ __('Total Amount') }}</th>
                        <th class="text-end">{{ __('Allowance') }}</th>
                        <th class="text-end">{{ __('Deduction') }}</th>
                        <th class="text-end">{{ __('Gross Amount') }}</th>
                        <th class="text-end">{{ __('Paid') }}</th>
                        <th class="text-end">{{ __('Due') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $previousMonthYear = '';
                    @endphp

                    @foreach ($payrolls as $payroll)
                        @php
                            $monthYear = $payroll->month . '-' . $payroll->year;
                        @endphp
                        @if ($previousMonthYear != $monthYear)
                            @php
                                $previousMonthYear = $monthYear;
                            @endphp

                            <tr>
                                <th class="text-start" colspan="11">{{ $monthYear }}</th>
                            </tr>
                        @endif

                        <tr>
                            <td class="text-start">{{ $payroll->voucher_no }}</td>

                            <td class="text-start">
                                @php
                                    $empId = $payroll->user_emp_id ? ' (' . $payroll->user_emp_id . ')' : '';
                                    $userName = $payroll->user_prefix . ' ' . $payroll->user_name . ' ' . $payroll->user_last_name . $empId;
                                @endphp
                                {{ $userName }}
                            </td>

                            <td class="text-start">
                                @php
                                    $branch = '';
                                    if ($payroll->branch_id) {
                                        if ($payroll->parent_branch_name) {
                                            $branch = $payroll->parent_branch_name . '(' . $payroll->branch_area_name . ')';
                                        } else {
                                            $branch = $payroll->branch_name . '(' . $payroll->branch_area_name . ')';
                                        }
                                    } else {
                                        $branch = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branch }}
                            </td>

                            <td class="text-start">
                                @php
                                    $payStatus = '';
                                    if ($payroll->due <= 0) {
                                        $payStatus = __('Paid');
                                    } elseif ($payroll->due > 0 && $payroll->due < $payroll->gross_amount) {
                                        $payStatus = __('Partial');
                                    } elseif ($payroll->gross_amount == $payroll->due) {
                                        $payStatus = __('Due');
                                    }
                                @endphp
                                {{ $payStatus }}
                            </td>

                            <td class="text-start">{{ $payroll->duration_unit }}</td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}
                                @php
                                    $totalPayroll += $payroll->gross_amount;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->paid) }}
                                @php
                                    $totalPaid += $payroll->paid;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($payroll->due) }}
                                @php
                                    $totalDue += $payroll->due;
                                @endphp
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
                        <th class="text-end">{{ __('Total Payroll') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Paid') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalPaid) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">{{ __('Total Due') }} : </th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($totalDue) }}
                        </td>
                    </tr>
                </thead>
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
