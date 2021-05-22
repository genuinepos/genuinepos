@foreach ($quotations as $quotation)
    <tr>
        <td class="text-start">{{ $loop->index + 1 }}</td>
        <td class="text-start">{{ $quotation->invoice_id }}</td>
        <td class="text-start">{{ $quotation->customer_id ? $quotation->customer->name : 'Walk-In-Customer' }}</td>
        <td class="text-start">{{ $quotation->total_payable_amount }}</td>
        <td class="text-start">
            <a id="editInvoice" href="{{ route('sales.pos.edit', $quotation->id) }}" class=""><i class="far fa-edit text-dark text-muted"></i></a>
            <a id="delete" href="{{ route('sales.delete', $quotation->id) }}" class=""><i class="far fa-trash-alt text-danger"></i></a>
            <a href="{{ route('sales.print', $quotation->id) }}" id="only_print"><i class="fas fa-print text-dark text-muted"></i></a>
        </td>
    </tr>  
@endforeach