<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Date</th>
            <th class="text-start">Description</th>
            <th class="text-start">Created By</th>
            <th class="text-start">Debit</th>
            <th class="text-start">Credit</th>
            <th class="text-start">Balance</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($filterAccountCashFlows as $cashFlow)
        <tr>
            <td class="text-start">{{ date('d/m/Y', strtotime($cashFlow->date)) }}</td> 
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
                    @if($cashFlow->purchase_payment->payment_on == 1)
                       {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return' }}  <br>
                        <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->supplier->name }}</span>  <br>
                        <span class="mt-1">{!! '<b>Purchase Invoice : </b>'. '<span class="text-primary">'.$cashFlow->purchase_payment->purchase->invoice_id.'</span>' !!}</span> <br>
                        <span class="mt-1">{!! '<b>Pay Invoice : </b>'. '<span class="text-primary">'. $cashFlow->purchase_payment->invoice_id.'</span>' !!}</span>
                    @else   
                        {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return' }} <br>
                        <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->supplier->name }}</span>  <br>
                        <span class="mt-1">{!! '<b>Payment Invoice : </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>  <br>
                        <span class="mt-1">{!! '<b>Pay Invoice : </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>
                    @endif
                @elseif($cashFlow->transaction_type == 2)  
                    @if($cashFlow->sale_payment->payment_on == 1)
                        {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return' }} <br>
                        <span class="mt-1">Customer : {{ $cashFlow->sale_payment->customer ? $cashFlow->sale_payment->customer->name : 'Walk-In-Customer' }}</span>  <br>
                        <span class="mt-1">{!! '<b>Sale Invoice</b>: '. $cashFlow->sale_payment->sale->invoice_id !!}</span><br>
                        <span class="mt-1">{!! '<b>Pay Invoice : </b>'. $cashFlow->sale_payment->invoice_id !!}</span>
                    @else   
                        {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return' }}  <br>
                        <span class="mt-1">Customer : {{ $cashFlow->sale_payment->customer ? $cashFlow->sale_payment->customer->name : 'Walk-In-Customer' }}</span>  <br>
                        <span class="mt-1">{!! '<b>Payment Invoice :<b>'. $cashFlow->sale_payment->invoice_id !!}</b></span> <br> 
                        <span class="mt-1">{!! '<b>Pay Invoice : </b>'. $cashFlow->sale_payment->invoice_id !!}</span>
                    @endif
                @elseif($cashFlow->transaction_type == 6)  
                    <b>Expense</b><br>
                    <span class="mt-1"><b>Expanse Invoice :</b> {!! '<span class="text-primary">'.$cashFlow->expanse_payment->expense->invoice_id.'</span>'  !!}</span>  <br>
                    <span class="mt-1">{!! '<b>Pay Invoice : </b>'.'<span class="text-primary">'. $cashFlow->expanse_payment->invoice_id.'</span>' !!}</span> 
                @elseif($cashFlow->transaction_type == 9)  
                    <b>Received Amount By Receipt</b><br>
                    <b>Customer : </b> {{ $cashFlow->money_receipt->customer->name }}<br>
                    <span class="mt-1"><b>Voucher No :</b> {!! '<span class="text-primary">'.$cashFlow->money_receipt->invoice_id.'</span>'  !!}</span>  
                @elseif($cashFlow->transaction_type == 8)  
                    <b>Payroll Payment</b><br>
                    <b>Reference No : </b> {{ $cashFlow->payroll->reference_no }}<br>
                    <span class="mt-1"><b>Payment Voucher No :</b> {!! '<span class="text-primary">'.$cashFlow->payroll_payment->reference_no.'</span>'  !!}</span>  
                @elseif($cashFlow->transaction_type == 10)  
                    <b>{{ $cashFlow->loan->type == 1 ? 'Pay Loan' : 'Get Loan' }}</b><br>
                    <b>{{ $cashFlow->loan->company->name }}</b><br>
                    <b>Reference No : </b> {{ $cashFlow->loan->reference_no }}
                @endif
            </td> 
            <td class="text-start">{{ $cashFlow->admin ? $cashFlow->admin->prefix.' '.$cashFlow->admin->name.' '.$cashFlow->admin->last_name : '' }}</td>
            <td class="text-start">{{ $cashFlow->debit }}</td>
            <td class="text-start">{{ $cashFlow->credit }}</td>
            <td class="text-start">{{ $cashFlow->balance }}</td>
            <td class="text-center">
                <div class="dropdown table-dropdown">
                    @if ($cashFlow->transaction_type == 4 || $cashFlow->transaction_type == 5)
                        <a href="{{ route('accounting.accounts.account.delete.cash.flow', $cashFlow->id) }}" class="btn btn-sm btn-danger" id="delete">Delete</a>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[0, 'desc']]
    });
</script>
