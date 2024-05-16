@foreach ($orders as $order)
    @php
        $accountService = new \App\Services\Accounts\AccountBalanceService();
        $amounts = $accountService->accountBalance($order->customer_account_id);
    @endphp
    <li>
        <a href="#" id="selected_order" class="name" data-sales_order_id="{{ $order->sales_order_id }}" data-customer_account_id="{{ $order->customer_account_id }}" data-customer_name="{{ $order->customer_name . '/' . $order->customer_phone }}" data-closing_balance="{{ $amounts['closing_balance_in_flat_amount'] }}" data-default_balance_type="{{ $order->default_balance_type }}">{{ $order->order_id }}</a>
    </li>
@endforeach
