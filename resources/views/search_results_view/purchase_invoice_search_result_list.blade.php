@foreach ($purchases as $purchase)

    {{-- @php
        $accountService = new App\Utils\AccountService();
        $amounts = $accountService->accountClosingBalance($purchase->supplier_account_id);
        $amounts['closing_balance_string']
    @endphp --}}
    <li>
        <a href="#" id="selected_invoice" class="name" data-purchase_id="{{ $purchase->purchase_id }}" data-warehouse_id="{{ $purchase->warehouse_id }}" data-warehouse_name="{{ $purchase->warehouse_name }}" data-supplier_account_id="{{ $purchase->supplier_account_id }}" data-current_balance="0.00">{{ $purchase->p_invoice_id }}</a>
    </li>
@endforeach
