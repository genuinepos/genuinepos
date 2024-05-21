@foreach ($purchases as $purchase)
    @php
        $accountService = new \App\Services\Accounts\AccountBalanceService();
        $amounts = $accountService->accountBalance($purchase->supplier_account_id);
    @endphp
    <li>
        <a href="#" id="selected_invoice" class="name" data-purchase_id="{{ $purchase->purchase_id }}" data-warehouse_id="{{ $purchase->warehouse_id }}" data-warehouse_name="{{ $purchase->warehouse_name }}" data-supplier_name="{{ $purchase->supplier_name . '/' . $purchase->supplier_phone }}" data-supplier_account_id="{{ $purchase->supplier_account_id }}" data-closing_balance="{{ $amounts['closing_balance_in_flat_amount'] }}" data-default_balance_type="{{ $purchase->default_balance_type }}">{{ $purchase->p_invoice_id }}</a>
    </li>
@endforeach
