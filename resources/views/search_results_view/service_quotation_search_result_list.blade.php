@php
    $accountService = new \App\Services\Accounts\AccountBalanceService();
@endphp
@foreach ($quotations as $quotation)
    @php
        $amounts = $accountService->accountBalance($quotation->customer_account_id);
    @endphp
    <li>
        <a href="#" id="selected_quotation" class="name" data-quotation_id="{{ $quotation->id }}" data-customer_account_id="{{ $quotation->customer_account_id }}" data-current_balance="{{ $amounts['closing_balance_in_flat_amount'] }}">{{ $quotation->quotation_id }}</a>
    </li>
@endforeach
