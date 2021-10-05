    <style>
        .table-striped tbody tr:nth-of-type(odd) {background-color: #cbe4ee }
        .table-striped tbody tr:nth-of-type(odd) {background-color: #cbe4ee;} 
    </style>
<table class="display data_tbl2 data__table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th></th>
            <th>Description</th>
            <th>Invoice ID</th>
            <th>Voucher No</th>
            <th>Payment Method</th>
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
                        <b>{{ $ledger->sale_payment->account ? $ledger->sale_payment->account->name : '' }} 
                        {!! $ledger->sale_payment->account ? ' A/C '.$ledger->sale_payment->account->account_number.'<br>' : '' !!}</b>
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
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ledger->amount) }}</td>
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

<script>
    $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}", exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary', title : "Customer Ledger Of {{$customer->name.' (ID:'.$customer->contact_id.')'}}",exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>