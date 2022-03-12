@foreach ($invoices as $invoice)
    <li>
        <a id="selected_invoice" class="name" data-id="{{ $invoice->id }}" href="#">
            {{ $invoice->invoice_id }}
        </a>
    </li>
@endforeach