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
        size: a4 landscape;
        margin-top: 0.8cm;
        margin-left: 20px;
        margin-right: 20px;
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
                        <img style="height: 45px; width:200px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->parentBranch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                    @endif
                @else
                    @if (auth()->user()?->branch?->logo)
                        <img style="height: 45px; width:200px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->logo) }}">
                    @else
                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ auth()->user()?->branch?->name }}</span>
                    @endif
                @endif
            @else
                @if ($generalSettings['business_or_shop__business_logo'] != null)
                    <img style="height: 45px; width:200px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
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
            <h6 style="text-transform:uppercase;"><strong>{{ __('Attendance Report') }}</strong></h6>
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
        <div class="col-12">
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
            <p><strong>{{ __('Shop/Business') }} : </strong> {{ $filteredBranchName ? $filteredBranchName : $ownOrParentbranchName }}</p>
        </div>
    </div>

    @php
        $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
        $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    @endphp

    <div class="row mt-1">
        <div class="col-12">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        @foreach ($datesAndDays as $key => $value)
                            @php
                                $__value = explode(' ', $value);
                                $date = $__value[0];
                                $day = $__value[1];
                            @endphp
                            <th>{!! $date . '<br/>' . $day !!}</th>
                        @endforeach
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</td>
                            @php
                                $branchId = $user?->branch?->parentBranch ? $user?->branch?->parentBranch?->id : $user->branch_id;
                                $presentDays = [];
                                if (count($users) > 0) {
                                    foreach ($user->attendances as $attendance) {
                                        $presentDays[] = date('Y-m-d', strtotime($attendance->clock_in_ts));
                                    }
                                }
                                $totalPresent = 0;
                                $totalAbsent = 0;
                                $totalDays = count($dates);

                                $holidayDates = [];
                                foreach ($holidayBranches->where('branch_id', $branchId) as $holidayBranch) {
                                    if ($holidayBranch->holiday) {
                                        $first = $holidayBranch->holiday->start_date;
                                        $last = $holidayBranch->holiday->end_date;
                                        $step = '+1 day';
                                        $output_format = 'Y-m-d';
                                        $current = strtotime($first);
                                        $last = strtotime($last);

                                        while ($current <= $last) {
                                            $holidayDates[] = date($output_format, $current);
                                            $current = strtotime($step, $current);
                                        }
                                    }
                                }

                                $leaveDays = [];
                                foreach ($user->leaves as $leave) {
                                    $first = $leave->start_date;
                                    $last = $leave->end_date;
                                    $step = '+1 day';
                                    $output_format = 'Y-m-d';
                                    $current = strtotime($first);
                                    $last = strtotime($last);

                                    while ($current <= $last) {
                                        $leaveDays[] = date($output_format, $current);
                                        $current = strtotime($step, $current);
                                    }
                                }
                            @endphp

                            @if ($found == true)
                                @foreach ($dates as $date)
                                    <td>
                                        @if (strtotime($date) > strtotime(date('Y-m-d')))
                                            <span class="fw-bold">-</span>
                                        @else
                                            @if (in_array($date, $holidayDates))
                                                <span class="fw-bold" title="Holiday">📅</span>
                                            @elseif(in_array($date, $leaveDays))
                                                <span class="fw-bold" title="Leave" style="font-size: 15px;">✈</span>
                                            @else
                                                @if (in_array($date, $presentDays))
                                                    <span class="fw-bold">✔</span>
                                                    @php
                                                        $totalPresent += 1;
                                                    @endphp
                                                @else
                                                    <span>❌</span>
                                                    @php
                                                        $totalAbsent += 1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                @foreach ($dates as $date)
                                    <td>
                                        <span class="fw-bold">-</span>
                                    </td>
                                @endforeach
                            @endif

                            <td class="fw-bold"><span style="font-size: 9px;">❌</span>{{ $totalAbsent }}/✔{{ $totalPresent }}/{{ $totalDays }}</td>
                        </tr>
                    @endforeach
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
