<table class="display data_tbl data__table">
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
                                <span class="text-muted fw-bold">-</span>
                            @else
                                @if (in_array($date, $holidayDates))
                                    <span class="fw-bold" title="Holiday">📅</span>
                                @elseif(in_array($date, $leaveDays))
                                    <span class="fw-bold text-danger" title="Leave" style="font-size: 15px;">✈</span>
                                @else
                                    @if (in_array($date, $presentDays))
                                        <span class="text-success fw-bold">✔</span>
                                        @php
                                            $totalPresent += 1;
                                        @endphp
                                    @else
                                        <span class="text-danger">❌</span>
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
                            <span class="text-muted fw-bold">-</span>
                        </td>
                    @endforeach
                @endif

                <td class="fw-bold"><span class="text-danger">{{ $totalAbsent }}</span>/<span class="text-success">{{ $totalPresent }}</span>/{{ $totalDays }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable();
    var html = '';

    html += '<span class="ms-2" style="font-size:11px;"> ❌ = Absent,</span>';
    html += '<span class="ms-2" style="font-size:11px;"> <span class="text-success">✔</span> = present,</span>';
    html += '<span class="ms-2" style="font-size:11px;"> 📅 = Holiday,</span>';
    html += '<span class="ms-2 style="font-size: 15px;"> <span class="text-danger">✈</span> = Leave</span>';
    $('.dataTables_filter').append(html);
        // sales_table.buttons().container().appendTo('#exportButtonsContainer');
</script>
