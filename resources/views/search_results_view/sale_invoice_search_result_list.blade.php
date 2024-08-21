@foreach ($sales as $sale)
    @php
        $accountService = new \App\Services\Accounts\AccountBalanceService();
        $amounts = $accountService->accountBalance($sale->customer_account_id);
    @endphp
    <li>
        <a href="#" id="selected_invoice" class="name" data-sale_id="{{ $sale->sale_id }}" data-customer_account_id="{{ $sale->customer_account_id }}" data-current_balance="{{ $amounts['closing_balance_in_flat_amount'] }}">{{ $sale->invoice_id }}</a>
    </li>
@endforeach
