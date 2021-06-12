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
                    @endif
                    <h6><b>Payroll Report</b></h6>
                    <h6>Payroll Of {{ $s_date .' - '. $e_date }}</h6>
                </div>
            </div>
        </div>
    </div>
    <br>
    <table class="table modal-table table-sm table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Month/Year</th>
                <th>Reference No</th>
                <th>Gross Amount</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Payment Status</th>
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
                    <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                    <td>{{ $row->emp_prefix.' '.$row->emp_name.' '.$row->emp_last_name }}-{{ $row->emp_id }}</h6></td>
                    <td>{{ $row->department_name }}</td>
                    <td>{{ $row->month }}/{{ $row->year }}</td>
                    <td>{{ $row->reference_no }}</td>
                    <td>{{ $row->gross_amount }}</td>
                    <td>{{ $row->paid }}</td>
                    <td>{{ $row->due }}</td>
                    <td>
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
    <br>
    <div class="footer_area text-center">
        <small>Developed by <b>SpeedDigit Pvt. Ltd.</b></small>
    </div>
</div>