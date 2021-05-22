<table class="display data_tbl data__table table-striped table-bordered">
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

    <thead>
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
                    <td>{{ date('d/m/Y', strtotime($ledger->purchase_payment->date)) }}</td> 
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
    </thead>
</table>