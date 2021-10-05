
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
        <div class="col-6">
            <ul class="list-unstyled">
                <li><strong>Customer : </strong> {{ $customer->name }} (ID: {{ $customer->contact_id }})</li>
                <li><strong>Phone : </strong> {{ $customer->phone }}</li>
                <li><strong>Address : </strong> {{ $customer->address  }}</li> 
            </ul>
        </div>
    </div>
</div>
<br>
<div class="row" >
    <div class="col-12" >
        <table class="table modal-table table-sm table-bordered" >
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th class="text-start"></th>
                    <th class="text-start">Description</th>
                    <th class="text-start">Invoice ID</th>
                    <th class="text-start">Voucher No</th>
                    <th class="text-start">Payment Method</th>
                    <th class="text-end">Debit({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                    <th class="text-end">Credit({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($ledgers as $ledger)
                    <tr>
                        @if ($ledger->row_type == 1)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->sale->date)) }}</td> 
                            <td class="text-start">Cr</td>
                            <td class="text-start"><b>Sale :</b>  
                                @foreach ($ledger->sale->sale_products as $item)
                                    {{ Str::limit($item->product->name, 15)  }} {{$item->variant ? $item->variant->variant_name : ''}}, 
                                @endforeach
                            </td>
                            <td class="text-start">{{ $ledger->sale->invoice_id }}</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($ledger->sale->total_payable_amount) }}
                            </td>
                            <td class="text-start">---</td>
                        @elseif($ledger->row_type == 2)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->sale_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->sale_payment->payment_type == 1 ? 'Dr' : 'Cr' }}</td>
                            <td class="text-start">
                                {{ $ledger->sale_payment->payment_type == 1 ? 'Receive Payment' : 'Sale Return Payment' }}<br>
                                {{ $ledger->sale_payment->account ? $ledger->sale_payment->account->name : '' }}
                                {{ $ledger->sale_payment->account ? ' A/C '.$ledger->sale_payment->account->account_number : '' }} 
                                Payment For Sale : (Invoice ID {{ $ledger->sale_payment->sale->invoice_id }})
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ $ledger->sale_payment->invoice_id }}</td>
                        
                            <td class="text-start">{{ $ledger->sale_payment->pay_mode }}</td>
                            @if ($ledger->sale_payment->payment_type == 1)
                                <td class="text-start">---</td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->sale_payment->paid_amount) }}
                                </td>
                            @else   
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->sale_payment->paid_amount) }}
                                </td>
                                <td>---</td>  
                            @endif
                        @elseif ($ledger->row_type == 4)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                            <td class="text-start">Dr</td>
                            <td class="text-start">
                                Receive Payment By Money Receipt
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start"> {{ $ledger->money_receipt->invoice_id }}</td>
                            <td class="text-start">{{ $ledger->money_receipt->payment_method }}</td>
                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($ledger->amount) }}</td>
                            <td class="text-start">---</td> 
                        @elseif($ledger->row_type == 5)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->customer_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->customer_payment->type == 1 ? 'Dr' : 'Cr' }}</td>
                            <td class="text-start">
                                {{ $ledger->customer_payment->type == 1 ? 'Received From Customer(Sale Due)' : 'Paid To Customer (Sale Return Due)' }}
                                <b>{!! $ledger->customer_payment->account ? '<br>'.$ledger->customer_payment->account->name : '' !!}
                                {!! $ledger->customer_payment->account ? 'A/C '.$ledger->customer_payment->account->account_number: '' !!}</b>
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ $ledger->customer_payment->voucher_no }}</td>
                            <td class="text-start">{{ $ledger->customer_payment->pay_mode }}</td>
                            @if ($ledger->customer_payment->type == 1)
                                <td class="text-start">---</td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->customer_payment->paid_amount) }}
                                </td>
                            @else   
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->customer_payment->paid_amount) }}
                                </td>
                                <td class="text-start">---</td>
                            @endif
                        @else 
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                            <td class="text-start">Cr</td>
                            <td class="text-start">Opening Balance</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>   
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($ledger->amount) }}
                            </td>
                            <td class="text-start">---</td>   
                        @endif
                    </tr>
                @endforeach
            </tbody>
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
