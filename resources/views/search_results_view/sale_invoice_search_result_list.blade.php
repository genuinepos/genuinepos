@foreach ($sales as $sale)

    {{-- @php
        $accountService = new App\Utils\AccountService();
        $amounts = $accountService->accountClosingBalance($purchase->supplier_account_id);
        $amounts['closing_balance_string']
    @endphp --}}
    <li>
        <a href="#" id="selected_invoice" class="name" data-sale_id="{{ $sale->sale_id }}" data-customer_account_id="{{ $sale->customer_account_id }}" data-current_balance="0.00">{{ $sale->invoice_id }}</a>
    </li>
@endforeach
