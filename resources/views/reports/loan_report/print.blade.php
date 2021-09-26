<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 6px;margin-right: 6px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p><b>All Business Location</b></p>
        @elseif ($branch_id == 'NULL')
            <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        @else
            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp
            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)
            <p><b>Date :</b>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>Loan Report </b></h6>
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
<br>
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
                    <th class="text-start">Loan Amount({{json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-start">Total Paid({{json_decode($generalSettings->business, true)['currency']}})</th>
                    <th class="text-start">Laon Due({{json_decode($generalSettings->business, true)['currency']}})</th>
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
                        <td class="text-start">
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
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($loan->loan_amount) }}
                            @php
                                $totalLoan += $loan->loan_amount;
                            @endphp
                        </td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($loan->total_paid) }}
                            @php
                                $totalPaid += $loan->total_paid;
                            @endphp
                        </td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($loan->due) }}
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

<div class="row" >
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-end">Total Pay loan :</th>
                    <td class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($totalLoan) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Paid :</th>
                    <td class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($totalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Payment Due :</th>
                    <td class="text-end">{{json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($totalDue) }}</td>
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

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        Print Date: {{ date('d-m-Y , h:iA') }}
    </small>
</div>