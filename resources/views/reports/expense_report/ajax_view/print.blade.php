@php
    $totalExpense = 0;
    $totalPaid = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
            <p><b>All Business Location.</b></p> 
        @elseif ($branch_id == 'NULL')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        @else
            @php
                $branch = DB::table('branches')->where('id', $branch_id)->select('name', 'branch_code')->first();
            @endphp
            {{ $branch->name.' '.$branch->branch_code }}
        @endif
        <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        <p><b>Expense Report </b></p>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">Reference No</th>
                    <th class="text-start">B.Location</th>
                    <th class="text-start">Expense For</th>
                    <th class="text-start">Payment Status</th>
                    <th class="text-start">Amount</th>
                    <th class="text-start">Paid</th>
                    <th class="text-start">Payment Status</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($expenses as $ex)
                @php
                    $totalPaid += $ex->net_total;
                @endphp
                    <tr>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date)) }}</td>
                        <td class="text-start">{{ $row->invoice_id }}</td>
                        <td class="text-start">{{ $payment->customer_name ? $payment->customer_name : 'Walk-In-Customer' }}</td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b> {{ $payment->paid_amount }}</td>
                        <td class="text-start">{{ $payment->pay_mode }}</td>
                        <td class="text-start">{{ $payment->sale_invoice }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">Total Paid :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalPaid, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Expense :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalExpense, 0, 2) }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small> 
        </div>
    </div>
@endif