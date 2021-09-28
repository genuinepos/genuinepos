@php
    $totalPaid = 0;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p> 
        @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif
        <h6 class="margin-top:10px;"><b>Accounts Cash Flow </b></h6> 
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
                        <td class="text-start">{{ $cashFlow->debit }}</td>
                        <td class="text-start">{{ $cashFlow->credit }}</td>
                        <td class="text-start">{{ $cashFlow->balance }}</td>
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