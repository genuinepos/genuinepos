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
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<div class="row">
    <div class="col-12 text-center">
        <h6 style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
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
        <div class="col-8">
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
                    <th class="text-end">
                        Debit({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                    <th class="text-end">
                        Credit({{ json_decode($generalSettings->business, true)['currency'] }})</th>
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
                            <td class="text-end"> 
                                {{ App\Utils\Converter::format_in_bdt($ledger->purchase->total_purchase_amount) }}
                            </td>
                        @elseif($ledger->row_type == 2)
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase_payment->date)) }}</td> 
                            <td class="text-start">{{ $ledger->purchase_payment->payment_type == 1 ? 'Cr' : 'Dr' }}</td>
                            <td class="text-start">
                                @if ($ledger->purchase_payment->is_advanced == 1)
                                    <b>PO Advance Payment</b><br>
                                @else 
                                    <b> {{ $ledger->purchase_payment->payment_type == 1 ? 'Purchase Due Payment' : 'Return Due Payment' }}</b><br>
                                @endif
                                {{ $ledger->purchase_payment->account ? $ledger->purchase_payment->account->name : '' }}
                                {{ $ledger->purchase_payment->account ? ' A/C '.$ledger->purchase_payment->account->account_number : '' }} 
                                Payment For :{{ $ledger->purchase_payment->purchase->invoice_id }}
                            </td>
                            <td class="text-start">---</td>
                            <td class="text-start">{{ $ledger->purchase_payment->invoice_id }}</td>
                            <td class="text-start">{{ $ledger->purchase_payment->pay_mode }}</td>
                            @if ($ledger->purchase_payment->payment_type == 1)
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->purchase_payment->paid_amount) }}
                                </td>
                                <td class="text-start">---</td>
                            @else   
                                <td class="text-start">---</td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->purchase_payment->paid_amount) }}
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
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->supplier_payment->paid_amount) }}
                                </td>
                                <td class="text-start">---</td>
                            @else   
                                <td class="text-start">---</td>
                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($ledger->supplier_payment->paid_amount) }}
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
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($ledger->amount) }}
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
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="text-start"><strong>Opening Balance :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ App\Utils\Converter::format_in_bdt($supplier->opening_balance) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Purchase :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Total Paid :</strong></td>
                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-start"><strong>Balance Due :</strong></td>
                    <td class="text-start">
                        <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                        {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-12 text-center">
            <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small> 
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
    <small style="font-size: 5px;float:right;" class="text-end">
        Print Date: {{ date('d-m-Y , h:iA') }}
    </small>
</div>
