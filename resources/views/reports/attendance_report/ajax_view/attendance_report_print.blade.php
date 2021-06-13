@php use Carbon\Carbon; @endphp
<div class="print_area">
    <div class="heading_area">
        <div class="row">
            <div class="col-md-12">
                <div class="company_name text-center">
                    <h3><b>{{ json_decode($generalSettings->business, true)['shop_name'] }}</b> </h3>
                    @if ($branch_id != 'NULL' && $branch_id != '')
                        @php
                            $branch = DB::table('branches')->where('id', $branch_id)->first(['id', 'name', 'branch_code']);
                        @endphp
                        <h5><b>{{ $branch->name.'/'.$branch->branch_code }}</b> </h5>
                    @elseif($branch_id == '')
                        <h5><b>All Branch</b></h5> 
                    @endif
                    <h6><b>Attendance Report</b></h6>
                    <h6>Attendance Of {{ $s_date .' To '. $e_date }}</h6>
                </div>
            </div>
        </div>
    </div>
    <br>
    <table class="table modal-table table-sm table-bordered">
        <thead>
            <tr>
                <th class="text-start">Date</th>
                <th class="text-start">Employee</th>
                <th class="text-start">Clock In - Clock Out</th>
                <th class="text-start">Work Duration</th>
                <th class="text-start">Shift</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $row)
                <tr>
                    <td class="text-start">{{ date('d/m/Y', strtotime($row->at_date)) }}</td>
                    <td class="text-start">{{ $row->prefix.' '.$row->name.' '.$row->last_name }}-{{ $row->emp_id }}</h6></td>
                    <td class="text-start">
                        @php
                            $clockOut = $row->clock_out_ts ? ' - ' . date('h:i a', strtotime($row->clock_out)) : '';
                        @endphp
                        <b> {{ date('h:i a', strtotime($row->clock_in)) . $clockOut }}</b>
                    </td>
                    <td class="text-start">
                        @if ($row->clock_out_ts) 
                            @php
                                $startTime = Carbon::parse($row->clock_in);
                                $endTime = Carbon::parse($row->clock_out);
                                // $totalDuration = $startTime->diffForHumans($endTime);
                                $totalDuration = $endTime->diff($startTime)->format("%H:%I:%S");
                            @endphp
                            {{ $totalDuration }}
                        @else 
                            Clock-Out-does-not-exists
                        @endif
                    </td>
                    <td class="text-start">{{ $row->shift_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div class="footer_area text-center">
        <small>Developed by <b>SpeedDigit Pvt. Ltd.</b></small>
    </div>
</div>