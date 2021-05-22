@foreach ($drafts as $draft)
    <tr class="text-center">
        <td class="text-start">{{ $loop->index + 1 }}</td>
        <td class="text-start">{{ $draft->invoice_id }}</td>
        <td class="text-start">{{ $draft->customer_id ? $draft->customer->name : 'Walk-In-Customer' }}</td>
        <td class="text-start">{{ $draft->total_payable_amount }}</td>
        <td class="text-start">
            <a id="editInvoice" href="{{ route('sales.pos.edit', $draft->id) }}" class=""><i class="far fa-edit text-dark text-muted mr-1"></i></a>
            <a id="delete" href="{{ route('sales.delete', $draft->id) }}" class=""><i class="far fa-trash-alt text-danger mr-1"></i></a>
            <a href="{{ route('sales.print', $draft->id) }}" id="only_print" class=""><i class="fas fa-print text-dark text-muted mr-1"></i></a>
        </td>
    </tr>  
@endforeach