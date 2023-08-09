<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('menu.date')</th>
            <th class="text-start">@lang('menu.particular')</th>
            <th class="text-start">@lang('menu.voucher')</th>
            <th class="text-start">@lang('menu.debit')</th>
            <th class="text-start">@lang('menu.credit')</th>
            <th class="text-start">@lang('menu.balance')</th>
            <th class="text-center">@lang('menu.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accountCashFlows as $cashFlow)
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
                        <b>@lang('menu.deposit')</b>
                    @elseif($cashFlow->transaction_type == 7)
                        <b>@lang('menu.opening_balance')</b>
                    @elseif($cashFlow->transaction_type == 3)
                        @if($cashFlow->purchase_payment->payment_on == 1)
                            @if ($cashFlow->purchase_payment->is_advanced == 1)
                                <b>@lang('menu.po_advance_payment')</b><br>
                            @else
                                {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Payment' : 'Purchase Return' }}  <br>
                            @endif
                            <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->purchase->supplier->name }}</span>  <br>
                            <span class="mt-1">{!! '<b>Purchase Invoice </b>'. '<span class="text-primary">'.$cashFlow->purchase_payment->purchase->invoice_id.'</span>' !!}</span> <br>
                            <span class="mt-1">{!! '<b>Payment Voucher </b>'. '<span class="text-primary">'. $cashFlow->purchase_payment->invoice_id.'</span>' !!}</span>
                        @else
                            {{ $cashFlow->purchase_payment->payment_type == 1 ? 'Purchase Due Payment' : 'Purchase Return Due Payment' }} <br>
                            <span class="mt-1">{{ 'Supplier : ' .$cashFlow->purchase_payment->purchase->supplier->name }}</span>  <br>
                            <span class="mt-1">{!! '<b>Payment Voucher </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>  <br>
                            <span class="mt-1">{!! '<b>Payment Voucher </b>'. $cashFlow->purchase_payment->invoice_id !!}</span>
                        @endif
                    @elseif($cashFlow->transaction_type == 2)
                        @if($cashFlow->sale_payment->payment_on == 1)
                            {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Due Payment' : 'Sale Return Due Payment' }} <br>
                            <span class="mt-1">@lang('menu.customer') : {{ $cashFlow->sale_payment->sale->customer ? $cashFlow->sale_payment->sale->customer->name : 'Walk-In-Customer' }}</span>  <br>
                            <span class="mt-1">{!! '<b>Sale Invoice</b>: '. $cashFlow->sale_payment->sale->invoice_id !!}</span><br>
                            <span class="mt-1">{!! '<b>Payment Voucher </b>'. $cashFlow->sale_payment->invoice_id !!}</span>
                        @else
                            {{ $cashFlow->sale_payment->payment_type == 1 ? 'Sale Due Payment' : 'Sale Return' }}  <br>
                            <span class="mt-1">@lang('menu.customer') : {{ $cashFlow->sale_payment->sale->customer ? $cashFlow->sale_payment->sale->customer->name : 'Walk-In-Customer' }}</span>  <br>
                            <span class="mt-1">{!! '<b>Payment Invoice :<b>'. $cashFlow->sale_payment->invoice_id !!}</b></span> <br>
                        @endif
                    @elseif($cashFlow->transaction_type == 6)
                        <b>@lang('menu.expense')</b> <br>
                        <span class="mt-1"><b>@lang('menu.expense') @lang('menu.invoice') </b> {!! '<span class="text-primary">'.$cashFlow->expanse_payment->expense->invoice_id.'</span>'  !!}</span>  <br>
                        <span class="mt-1">{!! '<b>Payment Voucher </b>'.'<span class="text-primary">'. $cashFlow->expanse_payment->invoice_id.'</span>' !!}</span>
                    @elseif($cashFlow->transaction_type == 8)
                        <b>@lang('menu.payroll_payment')</b><br>
                        <b>@lang('menu.reference_no') </b> {{ $cashFlow->payroll->reference_no }}<br>
                        <span class="mt-1"><b>@lang('menu.payment_voucher_no') </b> {!! '<span class="text-primary">'.$cashFlow->payroll_payment->reference_no.'</span>'  !!}</span>
                    @elseif($cashFlow->transaction_type == 10)
                        <b>{{ $cashFlow->loan->type == 1 ? 'Pay Loan' : 'Get Loan' }}</b><br>
                        <b>{{ $cashFlow->loan->company->name }}</b><br>
                        <b>@lang('menu.reference_no') </b> {{ $cashFlow->loan->reference_no }}
                    @elseif($cashFlow->transaction_type == 11)
                        <b>{{ $cashFlow->loan_payment->payment_type == 1 ? 'Pay Loan Due Receive' : 'Get Loan Due Paid' }}</b><br/>
                        <b>@lang('menu.b_location') </b> {{ $cashFlow->loan_payment->branch ? $cashFlow->loan_payment->branch->name.'/'.$cashFlow->loan_payment->branch->branch_code.'(BL)' : $generalSettings['business__shop_name'] .'(HO)' }}<br/>
                        <b>@lang('menu.company')/@lang('menu.person')</b> {{ $cashFlow->loan_payment->company->name }}<br/>
                        <b>@lang('menu.payment_voucher_no') </b> {{ $cashFlow->loan_payment->voucher_no }}
                    @elseif($cashFlow->transaction_type == 12)
                        <b>{{ $cashFlow->supplier_payment->type == 1 ? 'Paid To Supplier(Purchase Due)' : 'Receive From Supplier(Return Due)' }}</b><br>
                        <b>@lang('menu.supplier') </b>{{ $cashFlow->supplier_payment->supplier->name }}<br>
                        <b>@lang('menu.payment_voucher_no') </b> {{ $cashFlow->supplier_payment->voucher_no }}
                    @elseif($cashFlow->transaction_type == 13)
                        <b>{{ $cashFlow->customer_payment->type == 1 ? 'Receive From Customer(Sale Due)' : 'Paid To Customer(Return Due)' }}</b><br>
                        <b>@lang('menu.customer') </b> {{ $cashFlow->customer_payment->customer->name }}<br>
                        <b>@lang('menu.payment_voucher_no') </b> {{ $cashFlow->customer_payment->voucher_no }}
                    @endif
                </td>
                <td class="text-start">{{ $cashFlow->admin ? $cashFlow->admin->prefix.' '.$cashFlow->admin->name.' '.$cashFlow->admin->last_name : '' }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->debit) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->credit) }}</td>
                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($cashFlow->balance) }}</td>
                <td class="text-center">
                    <div class="dropdown table-dropdown">
                        @if ($cashFlow->transaction_type == 4 || $cashFlow->transaction_type == 5)
                            <a href="{{ route('accounting.accounts.account.delete.cash.flow', $cashFlow->id) }}" class="btn btn-sm btn-danger" id="delete">@lang('menu.delete')</a>
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
        aaSorting: [[0, 'desc']],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>

