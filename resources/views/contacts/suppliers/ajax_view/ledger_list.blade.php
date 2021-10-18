<table class="display data_tbl2 data__table">
    <thead>
        <tr>
            <th>Date</th>
            <th></th>
            <th>Description</th>
            <th>P.Invoice ID</th>
            <th>Voucher No</th>
            <th>Payment Method</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($ledgers as $ledger)
            <tr>
                @if ($ledger->row_type == 1)
                    <td>
                        {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase->date)) }}
                    </td> 
                    <td>Dr</td>
                    <td><b>Purchase :</b> 
                        @foreach ($ledger->purchase->purchase_products as $item)
                            {{ Str::limit($item->product->name, 15)  }} {{$item->variant ? $item->variant->variant_name : ''}}, 
                        @endforeach
                    </td>
                    <td>{{ $ledger->purchase->invoice_id }}</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td> 
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->purchase->total_purchase_amount }}
                    </td>
                @elseif($ledger->row_type == 2)
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase_payment->date)) }}</td> 
                    <td>{{ $ledger->purchase_payment->payment_type == 1 ? 'Cr' : 'Dr' }}</td>
                    <td>
                        @if ($ledger->purchase_payment->purchase->purchase_status == 3)
                            @if ($ledger->purchase_payment->payment_type == 1)
                                <b>PO Advance Payment</b><br>
                            @else 
                                <b>Purchase Return Payment</b><br>
                            @endif
                        @else 
                            <b>
                                {{ $ledger->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return Payment' }}
                            </b> <br>
                        @endif
                        {{ $ledger->purchase_payment->account ? $ledger->purchase_payment->account->name.' A/C '.$ledger->purchase_payment->account->account_number : '' }} <br>
                        Payment For :{{ $ledger->purchase_payment->purchase->invoice_id }}
                    </td>
                    <td>---</td>
                    <td>{{ $ledger->purchase_payment->invoice_id }}</td>
                    <td>{{ $ledger->purchase_payment->pay_mode }}</td>
                    @if ($ledger->purchase_payment->payment_type == 1)
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->purchase_payment->paid_amount }}
                        </td>
                        <td>---</td>
                    @else   
                        <td>---</td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->purchase_payment->paid_amount }}
                        </td>  
                    @endif
                @elseif($ledger->row_type == 4)
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->supplier_payment->date)) }}</td> 
                    <td>{{ $ledger->supplier_payment->type == 1 ? 'Cr' : 'Dr' }}</td>
                    
                    <td>
                        {{ $ledger->supplier_payment->type == 1 ? 'Paid to Supplier' : 'Received From Supplier' }}
                         <b>
                            {!! $ledger->supplier_payment->account ? '<br>'.$ledger->supplier_payment->account->name : '' !!}
                            {!! $ledger->supplier_payment->account ? 'A/C '.$ledger->supplier_payment->account->account_number: '' !!}
                        </b> 
                    </td>
                    <td>---</td>
                    <td>{{ $ledger->supplier_payment->voucher_no }}</td>
                    <td>{{ $ledger->supplier_payment->pay_mode }}</td>
                    @if ($ledger->supplier_payment->type == 1)
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->supplier_payment->paid_amount }}
                        </td>
                        <td>---</td>
                    @else   
                        <td>---</td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->supplier_payment->paid_amount }}
                        </td>  
                    @endif
                @else 
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                    <td>Dr</td>
                    <td>Opening Balance</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>   
                    <td>---</td> 
                    <td>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->amount }}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    var ledgerTable = $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}",exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        // "ordering" : "false",

    });
    // ledgerTable.columns(0).data().sort((a, b)=> {return new Date(b.date) - new Date(a.date);});
</script>