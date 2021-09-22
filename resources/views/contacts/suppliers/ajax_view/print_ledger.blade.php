<div class="row">
    <div class="col-md-12 text-center">
        <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
        {{-- @if ($fromDate && $toDate)
            <p><b>Date :</b> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <b>To</b> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p> 
        @endif --}}
        <p><b>Supplier Ledger </b></p> 
    </div>
</div>
<br>

<div class="supplier_details_area">
    <div class="row">
        <div class="col-4">
            <ul class="list-unstyled">
                <li><strong>Supplier : </strong> {{ $supplier->name }} (ID: {{ $supplier->contact_id }})</li>
                <li><strong>Phone : </strong> {{ $supplier->phone }}</li>
                <li><strong>Address : </strong> {{ $supplier->address  }}</li> 
            </ul>
        </div>
    </div>
</div>
<br>

<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Date</th>
                    <th></th>
                    <th class="text-start">Description</th>
                    <th class="text-start">P.Invoice ID</th>
                    <th class="text-start">Voucher No</th>
                    <th class="text-start">Payment Method</th>
                    <th class="text-start">Debit</th>
                    <th class="text-start">Credit</th>
                </tr>
            </thead>
        
            <tbody>
                @foreach ($ledgers as $ledger)
                    <tr>
                        @if ($ledger->row_type == 1)
                            <td class="text-start">
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase->date)) }}
                            </td> 
                            <td class="text-start">Dr</td>
                            <td class="text-start"><b>Purchase :</b> 
                                @foreach ($ledger->purchase->purchase_products as $item)
                                    {{ Str::limit($item->product->name, 15)  }} {{$item->variant ? $item->variant->variant_name : ''}}, 
                                @endforeach
                            </td>
                            <td class="text-start">{{ $ledger->purchase->invoice_id }}</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start"> 
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $ledger->purchase->total_purchase_amount }}
                            </td>
                        @elseif($ledger->row_type == 2)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->purchase_payment->payment_type == 1 ? 'Cr' : 'Dr' }}</td>
                            <td class="text-start">
                                {{ $ledger->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return Payment' }} <br>
                                {{ $ledger->purchase_payment->account ? $ledger->purchase_payment->account->name : '' }}
                                {{ $ledger->purchase_payment->account ? ' A/C '.$ledger->purchase_payment->account->account_number : '' }} 
                                Payment For :{{ $ledger->purchase_payment->purchase->invoice_id }}
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ $ledger->purchase_payment->invoice_id }}</td>
                            <td class="text-start">{{ $ledger->purchase_payment->pay_mode }}</td>
                            @if ($ledger->purchase_payment->payment_type == 1)
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->purchase_payment->paid_amount }}
                                </td>
                                <td class="text-start">---</td>
                            @else   
                                <td class="text-start">---</td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->purchase_payment->paid_amount }}
                                </td>  
                            @endif
                        @elseif($ledger->row_type == 4)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->supplier_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->supplier_payment->type == 1 ? 'Cr' : 'Dr' }}</td>
                            
                            <td class="text-start">
                                {{ $ledger->supplier_payment->type == 1 ? 'Paid to Supplier' : 'Received From Supplier' }}
                                <b>
                                    {!! $ledger->supplier_payment->account ? '<br>'.$ledger->supplier_payment->account->name : '' !!}
                                    {!! $ledger->supplier_payment->account ? 'A/C '.$ledger->supplier_payment->account->account_number: '' !!}
                                </b> 
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ $ledger->supplier_payment->voucher_no }}</td>
                            <td class="text-start">{{ $ledger->supplier_payment->pay_mode }}</td>
                            @if ($ledger->supplier_payment->type == 1)
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->supplier_payment->paid_amount }}
                                </td>
                                <td class="text-start">---</td>
                            @else   
                                <td class="text-start">---</td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $ledger->supplier_payment->paid_amount }}
                                </td>  
                            @endif
                        @else 
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                            <td class="text-start">Dr</td>
                            <td class="text-start">Opening Balance</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>
                            <td class="text-start">---</td>   
                            <td class="text-start">---</td> 
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $ledger->amount }}
                            </td>
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
                        {{ bcadd($supplier->opening_balance, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Purchase :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($supplier->total_purchase, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Paid :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($supplier->total_paid, 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Balance Due :</strong></td>
                    <td class="text-start">
                        <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ bcadd($supplier->total_purchase_due, 0, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
