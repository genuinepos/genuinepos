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
                        <h5><b>{{ $branch->name.'/'.$branch->branch_code }}</b>(BR) </h5>
                    @elseif($branch_id == '')
                        <h5><b>All Branch</b></h5> 
                    @endif
                    <h6><b>Payroll Report</b></h6>
                    <h6>Payroll Payment Of <b>{{ $s_date .' To '. $e_date }}</b></h6>
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
                <th class="text-start">Payment Voucher No</th>
                <th class="text-start">Paid</th>
                <th class="text-start">Pay For(Payroll)</th>
                <th class="text-start">Paid By</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_paid = 0;
            @endphp
            @foreach ($payrollPayments as $row)
                @php
                    $total_paid += $row->paid;
                @endphp
                <tr>
                    <td class="text-start">{{ date('d/m/Y', strtotime($row->date)) }}</td>
                    <td class="text-start">{{ $row->prefix.' '.$row->name.' '.$row->last_name }}-{{ $row->emp_id }}</h6></td>
                    <td class="text-start">{{ $row->voucher_no }}</td>
                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ $row->paid }}</td>
                    <td class="text-start">{{ $row->reference_no }}</td>
                    <td class="text-start">{{ $row->pb_prefix.' '.$row->pb_name.' '.$row->pb_last_name }}-{{ $row->emp_id }}</h6></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-start"></th>
                <th class="text-end">Total : </th>
                <th class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ bcadd($total_paid, 0, 2) }}</th>
                <th>--</th>
                <th>--</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer_area text-center">
        <small>Developed by <b>SpeedDigit Pvt. Ltd.</b></small>
    </div>
</div>