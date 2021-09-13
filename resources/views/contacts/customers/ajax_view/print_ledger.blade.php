
<div class="row">
    <div class="col-md-12 text-center">

        <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
        {{-- @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif --}}
        
        <p><b>Customer Ledger </b></p> 
    </div>
</div>
<br>
<div class="customer_details_area">
    <div class="row">
        <div class="col-4">
            <ul class="list-unstyled">
                <li><strong>Customer : </strong> {{ $customer->name }} (ID: {{ $customer->contact_id }})</li>
                <li><strong>Phone : </strong> {{ $customer->phone }}</li>
                <li><strong>Address : </strong> {{ $customer->address  }}</li> 
            </ul>
        </div>
    </div>
</div>
<br>
@php
$index = 0;
$pageBreak = 1;
@endphp
<div class="row" >
    <div class="col-12" >
        <table class="table modal-table table-sm table-bordered" >
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start">Invoice/Voucher No</th>
                    <th class="text-start">Type</th>
                    <th class="text-start">Trans. Amount</th>
                    <th class="text-start">Debit</th>
                    <th class="text-start">Credit</th>
                    <th class="text-start">Payment Method</th>
                    <th class="text-start">Others</th>
                </tr>
            </thead>
            
            <tbody>
                @php
                    $totalSale = 0;
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @foreach ($ledgers as $ledger)
                    @php $index++; @endphp
                    <tr>
                        @if ($ledger->row_type == 1)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->sale->date)) }}</td> 
                            <td class="text-start">{{ $ledger->sale->invoice_id }}</td>
                            <td class="text-start">Sale</td>
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $ledger->sale->total_payable_amount }}
                                @php $totalSale += $ledger->sale->total_payable_amount; @endphp
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                        @elseif($ledger->row_type == 2)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->sale_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->sale_payment->invoice_id }}</td>
                            <td class="text-start">{{ $ledger->sale_payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return Payment' }}</td>
                            <td class="text-start">---</td>
                            @if ($ledger->sale_payment->payment_type == 1)
                                <td class="text-start">---</td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->sale_payment->paid_amount }}
                                    @php $totalCredit += $ledger->sale_payment->paid_amount; @endphp
                                </td>
                            @else   
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->sale_payment->paid_amount }}
                                    @php $totalDebit += $ledger->sale_payment->paid_amount; @endphp
                                </td>
                                <td class="text-start">---</td>  
                            @endif
                            <td>{{ $ledger->sale_payment->pay_mode }}</td>
                            <td>Payment For : {{ $ledger->sale_payment->sale->invoice_id }}</td>
                        @elseif ($ledger->row_type == 4)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                            <td class="text-start">---</td>
                            <td class="text-start">
                                Receive Payment By Money Receipt<br>
                                Voucher No: {{ $ledger->money_receipt->invoice_id }}
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $ledger->amount }}</td>
                                @php $totalCredit += $ledger->amount; @endphp
                            <td>
                                {{ $ledger->money_receipt->payment_method }}
                            </td>   
                            <td class="text-start">---</td> 
                        @elseif($ledger->row_type == 5)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->customer_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->customer_payment->voucher_no }}</td>
                            <td class="text-start">{{ $ledger->customer_payment->type == 1 ? 'Customer Payment(Sale Due)' : 'Customer Payment(Sale Return Due)' }}</td>
                            <td class="text-start">---</td>
                            @if ($ledger->customer_payment->type == 1)
                                <td class="text-start">---</td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->customer_payment->paid_amount }}
                                    @php $totalCredit += $ledger->customer_payment->paid_amount; @endphp
                                </td>
                            @else   
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->customer_payment->paid_amount }}
                                    @php $totalDebit += $ledger->customer_payment->paid_amount; @endphp
                                </td>
                                <td>---</td>
                            @endif
                            <td class="text-start">{{ $ledger->customer_payment->pay_mode }}</td>
                            <td class="text-start">{{ $ledger->customer_payment->type == 1 ? 'Direct Received From Customer' : 'Direct Paid To Customer' }}</td>
                        @else 
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                            <td class="text-start">---</td>
                            <td class="text-start">Opening Balance</td>
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $ledger->amount }}
                                @php $totalSale += $ledger->amount; @endphp
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>   
                            <td class="text-start">---</td> 
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-start">Total :</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency']. bcadd($totalSale, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency']. bcadd($totalDebit, 0, 2) }}</th>
                    <th class="text-start">{{ json_decode($generalSettings->business, true)['currency']. bcadd($totalCredit, 0, 2) }}</th>
                    <th class="text-start">---</th>
                    <th class="text-start">---</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-start"><strong>Opening Balance :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($customer->opening_balance, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Sale :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($customer->total_sale, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Paid :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($customer->total_paid, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Balance Due :</strong></td>
                    <td class="text-start">
                        <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($customer->total_sale_due, 0, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
