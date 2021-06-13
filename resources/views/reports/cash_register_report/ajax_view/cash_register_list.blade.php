@php
    use Carbon\Carbon;
@endphp

<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Open Time</th>
            <th class="text-start">Closed Time</th>
            <th class="text-start">Branch</th>
            <th class="text-start">User</th>
            <th class="text-start">Total Card Slip</th>
            <th class="text-start">Total Cheque</th>
            <th class="text-start">Total Cash</th>
            <th class="text-start">Status</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cash_registers as $cash_register)
            @php
                $total_card_slip = 0;
                $total_cheque = 0;
                $total_cash = 0;
                foreach ($cash_register->cash_register_transactions as $transaction) {
                    if ($transaction->transaction_type == 1) {
                        $total_cash += $transaction->amount;
                    }else {
                        foreach ($transaction->sale->sale_payments as $sale_payment) {
                            if ($sale_payment->pay_mode == 'Card') {
                                $total_card_slip += 1;
                            }elseif ($sale_payment->pay_mode == 'Cheque') {
                                $total_cheque += 1;
                            }elseif ($sale_payment->pay_mode == 'Cash') {
                                $total_cash += $sale_payment->paid_amount;
                            }
                        }
                    }
                }
            @endphp
            <tr>
                <td>{{ $cash_register->created_at->toFormattedDateString() }}</td>
                <td>{{ $cash_register->closed_at ? Carbon::createFromFormat('Y-m-d H:i:s', $cash_register->closed_at)->format('jS M, Y h:i A')  : '' }}</td>
                <td>{{ $cash_register->branch ? $cash_register->branch->name.' - '.$cash_register->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (Head Office)' }}</td>
                <td>
                    {{ $cash_register->admin->prefix .' '.$cash_register->admin->name.' '.$cash_register->admin->last_name }} <br>
                    {{ $cash_register->admin->email  }} 
                </td>
                
                <td><b>{{ $total_card_slip }}</b></td>
                <td><b>{{ $total_cheque }}</b></td>
                <td>
                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ number_format((float) $total_cash, 2, '.', '') }}</b>
                </td>
                <td>
                    {!! $cash_register->status == 1 ? '<span class="badge bg-success">Open</span>' : '<span class="badge bg-danger">Closed</span>' !!}
                </td>

                <td>
                   <a id="register_details_btn" href="{{ route('reports.get.cash.register.details', $cash_register->id) }}" class="btn btn-sm btn-primary">View</a>
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
    });
</script>