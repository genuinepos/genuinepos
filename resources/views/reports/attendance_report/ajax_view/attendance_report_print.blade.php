<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
</style>
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
                        <h5><b>{{ $branch->name.'/'.$branch->branch_code }}</b>(BL) </h5>
                    @elseif($branch_id == '')

                        <h5><b>@lang('menu.all_business_location')</b></h5>
                    @endif

                    <h6><b>@lang('menu.attendance_report')</b></h6>
                    @if ($fromDate && $toDate)
                        <p><b>@lang('menu.date') :</b>
                            {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                            <b>@lang('menu.to')</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <table class="table modal-table table-sm table-bordered">
        <thead>
            <tr>
                <th class="text-start">@lang('menu.date')</th>
                <th class="text-start">{{ __('Employee') }}</th>
                <th class="text-start">{{ __('Clock In - Clock Out') }}</th>
                <th class="text-start">{{ __('Work Duration') }}</th>
                <th class="text-start">@lang('menu.shift')</th>
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
                            {{ __('Clock-Out-does-not-exists') }}
                        @endif
                    </td>
                    <td class="text-start">{{ $row->shift_name ? $row->shift_name : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
