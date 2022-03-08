@foreach ($invoices as $invoice)
    <li>
        <a id="select_invoice" class="name" data-id="{{ $invoice->id }}" href="#">
            {{ $invoice->invoice_id }}
        </a>
    </li>
@endforeach