    <style>
        .table-striped tbody tr:nth-of-type(odd) {background-color: #cbe4ee }
        .table-striped tbody tr:nth-of-type(odd) {background-color: #cbe4ee;} 
    </style>
<table class="display data_tbl2 data__table table-striped">
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
                    <td>{{ date('d/m/Y', strtotime($ledger->sale->date)) }}</td> 
                    <td>{{ $ledger->sale->invoice_id }}</td>
                    <td>Sale</td>
                    <td>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->sale->total_payable_amount }}
                    </td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                @elseif($ledger->row_type == 2)
                    <td>{{ date('d/m/Y', strtotime($ledger->sale_payment->date)) }}</td> 
                    <td>{{ $ledger->sale_payment->invoice_id }}</td>
                    <td>{{ $ledger->sale_payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return Payment' }}</td>
                    <td>---</td>
                    @if ($ledger->sale_payment->payment_type == 1)
                        <td>---</td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->sale_payment->paid_amount }}
                        </td>
                    @else   
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->sale_payment->paid_amount }}
                        </td>
                        <td>---</td>  
                    @endif
                    <td>{{ $ledger->sale_payment->pay_mode }}</td>
                    <td>Payment For : {{ $ledger->sale_payment->sale->invoice_id }}</td>
                @elseif ($ledger->row_type == 4)
                    <td>{{ date('d/m/Y', strtotime($ledger->created_at)) }}</td> 
                    <td>---</td>
                    <td>
                        Receive Payment By Money Receipt<br>
                        Voucher No: {{ $ledger->money_receipt->invoice_id }}
                    </td>
                    <td>---</td>
                    <td>---</td>
                    <td>{{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ $ledger->amount }}</td>
                    <td>
                        {{ $ledger->money_receipt->payment_method }}
                    </td>   
                    <td>---</td> 
                @elseif($ledger->row_type == 5)
                    <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ledger->customer_payment->date)) }}</td> 
                    <td>{{ $ledger->customer_payment->voucher_no }}</td>
                    <td>{{ $ledger->customer_payment->type == 1 ? 'Customer Payment(Sale Due)' : 'Customer Payment(Sale Return Due)' }}</td>
                    <td>---</td>
                    @if ($ledger->customer_payment->type == 1)
                        <td>---</td>
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->customer_payment->paid_amount }}
                        </td>
                    @else   
                        <td>
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $ledger->customer_payment->paid_amount }}
                        </td>
                        <td>---</td>
                    @endif
                    <td>{{ $ledger->customer_payment->pay_mode }}</td>
                    <td>{{ $ledger->customer_payment->type == 1 ? 'Direct Received From Customer' : 'Direct Paid To Customer' }}</td>
                @else 
                    <td>{{ date('d/m/Y', strtotime($ledger->created_at)) }}</td> 
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
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}",exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        aaSorting: [[0, 'asc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>