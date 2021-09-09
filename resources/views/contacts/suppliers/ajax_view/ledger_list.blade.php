<table class="display data_tbl2 data__table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Invoice ID</th>
            <th>Type</th>
            <th>Total</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Payment Method</th>
            <th>Others</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($ledgers as $ledger)
            <tr>
                @if ($ledger->row_type == 1)
                    <td>{{ date('d/m/Y', strtotime($ledger->purchase->date)) }}</td> 
                    <td>{{ $ledger->purchase->invoice_id }}</td>
                    <td>Purchase</td>
                    <td> 
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->purchase->total_purchase_amount }}
                    </td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                @elseif($ledger->row_type == 2)
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->purchase_payment->date)) }}</td> 
                    <td>{{ $ledger->purchase_payment->invoice_id }}</td>
                    <td>{{ $ledger->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return Payment' }}</td>
                    <td>---</td>
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
                    <td>{{ $ledger->purchase_payment->pay_mode }}</td>
                    <td>Payment For : {{ $ledger->purchase_payment->purchase->invoice_id }}</td>
                @elseif($ledger->row_type == 4)
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->supplier_payment->date)) }}</td> 
                    <td>{{ $ledger->supplier_payment->voucher_no }}</td>
                    <td>{{ $ledger->supplier_payment->type == 1 ? 'Supplier Payment(Purchase Due)' : 'Supplier Payment(Purchase Return Due)' }}</td>
                    <td>---</td>
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
                    <td>{{ $ledger->supplier_payment->pay_mode }}</td>
                    <td>{{ $ledger->supplier_payment->type == 1 ? 'Direct Paid to Supplier' : 'Direct Received From Supplier' }}</td>
                @else 
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->created_at)) }}</td> 
                    <td>---</td>
                    <td>Opening Balance</td>
                    <td>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->amount }}
                    </td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>   
                    <td>---</td> 
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary', title : "Supplier Ledger Of {{$supplier->name.' (ID:'.$supplier->contact_id.')'}}",exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        aaSorting: [[0, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>