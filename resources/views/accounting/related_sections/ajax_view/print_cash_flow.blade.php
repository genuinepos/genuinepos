<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 6px;margin-right: 6px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>

@php
    $totalPaid = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address']  }}</p>
        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif
        <br>
        <h6>Accounts Cash Flow </h6> 
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">Account</th>
                    <th class="text-start">Description</th>
                    <th class="text-start">Created By</th>
                    <th class="text-start">Debit</th>
                    <th class="text-start">Credit</th>
                    <th class="text-start">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filterCashFlows as $cashFlow)
                    <tr>
                        <td class="text-start">{{ date('d/m/Y', strtotime($cashFlow->date)) }}</td> 
                        <td class="text-start">{{ $cashFlow->account->name }}</td>
                        <td class="text-start">
                            @if ($cashFlow->transaction_type == 4)
                                @if ($cashFlow->cash_type == 1)
                                    {!! '<b>Fund Transfer</b> (To: '. $cashFlow->receiver_account->name.')'!!}
                                @else  
                                    {!! '<b>Fund Transfer</b> (From: '. $cashFlow->sender_account->name.')'!!}  
                                @endif
                            @elseif($cashFlow->transaction_type == 5)
                                <b>Deposit</b>   
                            @elseif($cashFlow->transaction_type == 7)   
                                <b>Opening Balance</b>    
                            @elseif($cashFlow->transaction_type == 3)  
                                {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return' }}  <br>
                                <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->purchase->supplier->name }}</span>  <br>
                                <span class="mt-1">{!! '<b>Purchase Invoice : </b>'. '<span class="text-primary">'.$cashFlow->purchase_payment->purchase->invoice_id.'</span>' !!}</span> <br>
                                <span class="mt-1">{!! '<b>Payment Voucher : </b>'. '<span class="text-primary">'. $cashFlow->purchase_payment->invoice_id.'</span>' !!}</span>
                              
                            @elseif($cashFlow->transaction_type == 2)  
                                {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return' }} <br>
                                <span class="mt-1">Customer : {{ $cashFlow->sale_payment->sale->customer ? $cashFlow->sale_payment->sale->customer->name : 'Walk-In-Customer' }}</span>  <br>
                                <span class="mt-1">{!! '<b>Sale Invoice</b>: '. $cashFlow->sale_payment->sale->invoice_id !!}</span><br>
                                <span class="mt-1">{!! '<b>Payment Voucher : </b>'. $cashFlow->sale_payment->invoice_id !!}</span>
                            @elseif($cashFlow->transaction_type == 6)  
                                <b>Expense</b> <br>
                                <span class="mt-1"><b>Expense ReferenceID :</b> {!! '<span class="text-primary">'.$cashFlow->expanse_payment->expense->invoice_id.'</span>'  !!}</span>  <br>
                                <span class="mt-1">{!! '<b>Payment Voucher : </b>'.'<span class="text-primary">'. $cashFlow->expanse_payment->invoice_id.'</span>' !!}</span>  
                            @elseif($cashFlow->transaction_type == 8)  
                                <b>Payroll Payment</b><br>
                                <b>Reference No : </b> {{ $cashFlow->payroll->reference_no }}<br>
                                <span class="mt-1"><b>Payment Voucher No :</b> {!! '<span class="text-primary">'.$cashFlow->payroll_payment->reference_no.'</span>'  !!}</span>      
                            @elseif($cashFlow->transaction_type == 10)  
                                <b>{{ $cashFlow->loan->type == 1 ? 'Pay Loan' : 'Get Loan' }}</b><br>
                                <b>Loan By : </b> {{ $cashFlow->loan->loan_by }}<br>
                                <b>{{ $cashFlow->loan->company->name }}</b><br>
                                <b>Reference No : </b> {{ $cashFlow->loan->reference_no }}
                            @elseif($cashFlow->transaction_type == 11)  
                                <b>{{ $cashFlow->loan_payment->payment_type == 1 ? 'Pay Loan Due Receive' : 'Get Loan Due Paid' }}</b><br/>
                                <b>B.Location : </b> {{ $cashFlow->loan_payment->branch ? $cashFlow->loan_payment->branch->name.'/'.$cashFlow->loan_payment->branch->branch_code.'(BL)' : json_decode($generalSettings->business, true)['shop_name'] .'(HO)' }}<br/>
                                <b>Company/Person :</b> {{ $cashFlow->loan_payment->company->name }}<br/>
                                <b>Payment Voucher No : </b> {{ $cashFlow->loan_payment->voucher_no }}
                            @elseif($cashFlow->transaction_type == 12)  
                                <b>{{ $cashFlow->supplier_payment->type == 1 ? 'Paid To Supplier(Purchase Due)' : 'Receive From Supplier(Return Due)' }}</b><br>
                                <b>Supplier : </b>{{ $cashFlow->supplier_payment->supplier->name }}<br>
                                <b>Payment Voucher No : </b> {{ $cashFlow->supplier_payment->voucher_no }}
                            @elseif($cashFlow->transaction_type == 13)  
                                <b>{{ $cashFlow->customer_payment->type == 1 ? 'Receive From Customer(Sale Due)' : 'Paid To Customer(Return Due)' }}</b><br>
                                <b>Customer :</b> {{ $cashFlow->customer_payment->customer->name }}<br>
                                <b>Payment Voucher No : </b> {{ $cashFlow->customer_payment->voucher_no }}
                            @endif
                        </td> 
                        <td class="text-start">{{ $cashFlow->admin ? $cashFlow->admin->prefix.' '.$cashFlow->admin->name.' '.$cashFlow->admin->last_name : '' }}</td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->debit) }}</td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->credit) }}</td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->balance) }}</td>
                    </tr>
                @endforeach
            </tbody>
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