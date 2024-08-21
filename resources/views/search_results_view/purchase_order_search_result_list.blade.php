@foreach ($orders as $order)
    @php
        $accountService = new \App\Services\Accounts\AccountBalanceService();
        $amounts = $accountService->accountBalance($order->supplier_account_id);
    @endphp
    <li>
        <a href="#" id="selected_po" class="name" data-purchase_order_id="{{ $order->purchase_order_id }}" data-supplier_name="{{ $order->supplier_name . '/' . $order->supplier_phone }}" data-supplier_account_id="{{ $order->supplier_account_id }}" data-closing_balance="{{ $amounts['closing_balance_in_flat_amount'] }}" data-default_balance_type="{{ $order->default_balance_type }}">{{ $order->po_id }}</a>
    </li>
@endforeach
