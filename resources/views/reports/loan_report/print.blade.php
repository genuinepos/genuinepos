<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        @elseif ($branch_id == 'NULL')
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        @else
            @php
                $branch = DB::table('branches')->where('id', $branch_id)
                ->select('name', 'branch_code')
                ->first();
            @endphp
            {{ $branch->name.' '.$branch->branch_code }}
        @endif

        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif

        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <p><b>Loan Report </b></p>
    </div>
</div>

@if ($company_id)
    @php
        $company = DB::table('loan_companies')->where('id', $company_id)->first();
    @endphp
    <div class="customer_details_area">
        <div class="row">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li><strong>Company/People : </strong> {{ $company->name }}</li>
                    <li><strong>Phone : </strong> </li>
                    <li><strong>Address : </strong> </li> 
                </ul>
            </div>
        </div>
    </div>
    <br>
@endif

<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">B.Location</th>
                    <th class="text-start">Reference No</th>
                    <th class="text-start">Company/People</th>
                    <th class="text-start">Type</th>
                    <th class="text-start">Loan By</th>
                    <th class="text-start">Loan Amount</th>
                    <th class="text-start">Total Paid</th>
                    <th class="text-start">Laon Due</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                   $totalLoan = 0;
                   $totalPaid = 0;
                   $totalDue = 0;
                @endphp
                @foreach ($loans as $loan)
                    <tr>
                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($loan->report_date)) }}</td>
                        <td>
                            @if ($loan->b_name) 
                                {!! $loan->b_name . '/' . $loan->b_code . '(<b>BL</b>)' !!}
                             @else 
                                {!! json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)' !!}
                            @endif
                        </td>
                        <td class="text-start">{{ $loan->reference_no }}</td>
                        <td class="text-start">{{ $loan->c_name }}</td>
                        <td> 
                            @if ($loan->type == 1) 
                                Pay Loan
                            @else 
                                Receive Loan
                            @endif
                        </td>
                        <td class="text-start">
                            @if ($loan->loan_by) 
                                 {{ $loan->loan_by }}
                            @else 
                                 Cash Loan pay
                            @endif
                        </td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $loan->loan_amount }}
                            @php
                                $totalLoan += $loan->loan_amount;
                            @endphp
                        </td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $loan->total_paid }}
                            @php
                                $totalPaid += $loan->total_paid;
                            @endphp
                        </td>
                        <td class="text-start"><b>{{json_decode($generalSettings->business, true)['currency']}}</b>{{ $loan->due }}
                            @php
                                $totalDue += $loan->due;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div style="page-break-after: always;"></div>
<div class="row" >
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">Total Pay loan :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalLoan, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Paid :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalPaid, 0, 2) }}</th>
                </tr>

                <tr>
                    <th class="text-end">Total Payment Due :</th>
                    <th class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.bcadd($totalDue, 0, 2) }}</th>
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