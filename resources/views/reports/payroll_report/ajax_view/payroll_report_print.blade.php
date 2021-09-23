<style>
    @page {/* size:21cm 29.7cm; */ margin:1cm 1cm 1cm 1cm; *//* margin:20px 20px 10px; */mso-title-page:yes;mso-page-orientation: portrait;mso-header: header;mso-footer: footer;}
</style>
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
                        <h5><b>All Branch</b></h5> 
                    @endif
                    <h6><b>Payroll Report</b></h6>

                    @if ($s_date && $s_date)
                        <p><b>Payroll Of :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($s_date)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($s_date)) }} </p> 
                    @endif
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
                <th class="text-start">Department</th>
                <th class="text-start">Month/Year</th>
                <th class="text-start">Reference No</th>
                <th class="text-start">Gross Amount</th>
                <th class="text-start">Paid</th>
                <th class="text-start">Due</th>
                <th class="text-start">Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_gross = 0;
                $total_paid = 0;
                $total_due = 0;
            @endphp
            @foreach ($payrolls as $row)
                @php
                    $total_gross += $row->gross_amount;
                    $total_paid += $row->paid;
                    $total_due += $row->due;
                @endphp
                <tr>
                    <td class="text-start">{{ date('d/m/Y', strtotime($row->date)) }}</td>
                    <td class="text-start">{{ $row->emp_prefix.' '.$row->emp_name.' '.$row->emp_last_name }}-{{ $row->emp_id }}</h6></td>
                    <td class="text-start">{{ $row->department_name }}</td>
                    <td class="text-start">{{ $row->month }}/{{ $row->year }}</td>
                    <td class="text-start">{{ $row->reference_no }}</td>
                    <td class="text-start">{{ $row->gross_amount }}</td>
                    <td class="text-start">{{ $row->paid }}</td>
                    <td class="text-start">{{ $row->due }}</td>
                    <td class="text-start">
                        @if ($row->due <= 0) 
    	                    Paid
    	                @elseif($row->due > 0 && $row->due < $row->gross_amount) 
    	                    Partial
    	                @elseif($row->gross_amount == $row->due) 
    	                    Due
    	                @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"></th>
                <th>Total : </th>
                <th>${{ bcadd($total_gross, 0, 2) }}</th>
                <th>${{ bcadd($total_paid, 0, 2) }}</th>
                <th>${{ bcadd($total_due, 0, 2) }}</th>
                <th>--</th>
            </tr>
        </tfoot>
    </table>

    @if (env('PRINT_SD_OTHERS') == true)
        <div class="footer_area text-center">
            <small>Software by <b>SpeedDigit Pvt. Ltd.</b></small>
        </div>
    @endif
</div>