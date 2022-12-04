<table class="table modal-table table-sm table-striped">
    <thead>
        <tr>
            <th class="text-startx">@lang('menu.sl')</th>
            <th class="text-startx">@lang('menu.invoice_id')</th>
            <th class="text-startx">@lang('menu.customer')</th>
            <th class="text-startx">Payable Amount</th>
            <th class="text-startx">@lang('menu.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($holdInvoices as $holdInvoice)
            <tr>
                <td class="text-start">{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $holdInvoice->invoice_id }}</td>
                <td class="text-start">{{ $holdInvoice->customer_id ? $holdInvoice->customer->name : 'Walk-In-Customer' }}</td>
                <td class="text-start">{{ $holdInvoice->total_payable_amount }}</td>
                <td class="text-start">
                    <a id="editInvoice" href="{{ route('sales.pos.edit', $holdInvoice->id) }}" tabindex="-1"><i class="far fa-edit text-dark text-muted mr-1"></i></a>
                    <a id="delete" href="{{ route('sales.delete', $holdInvoice->id) }}" tabindex="-1"><i class="far fa-trash-alt text-danger mr-1"></i></a>
                </td>
            </tr>  
        @endforeach
    </tbody>
</table>