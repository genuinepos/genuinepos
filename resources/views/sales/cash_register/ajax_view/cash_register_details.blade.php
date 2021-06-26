@php
    use Carbon\Carbon;  
    $cash_in_hand = 0;
    $cash_payment = 0;
    $card_payment = 0;
    $cheque_payment = 0;
    $bank_payment = 0;
    $other_payment = 0;
    $advanced_payment = 0;
    $custom_payment = 0;
    $total_paid = 0;
    $total_due = 0;
    $total_sale = 0;
    $total_card_payment = 0;
    $total_cheque_payment = 0;
    // $total_sale_qty = 0;
    foreach ($activeCashRegister->cash_register_transactions as $register_transaction) {
        if ($register_transaction->transaction_type == 1) {
                $cash_in_hand = $register_transaction->amount;
        }else {
            $total_paid += $register_transaction->sale->paid;
            $total_due += $register_transaction->sale->due;
            $total_sale += $register_transaction->sale->total_payable_amount;
            
            // foreach ($register_transaction->sale->sale_products as $sale_product) {
            //     $total_sale_qty += $sale_product->quantity;
            // }

            foreach ($register_transaction->sale->sale_payments as $sale_payment) {
                if ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Cash') {
                    $cash_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Card') {
                    $total_card_payment += 1;
                    $card_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Cheque') {
                    $total_cheque_payment += 1;
                    $cheque_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Bank-Transfer') {
                    $bank_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Other') {
                    $other_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Advanced') {
                    $advanced_payment += $sale_payment->paid_amount;
                }elseif ($sale_payment->pay_mode && $sale_payment->pay_mode == 'Custom') {
                    $custom_payment += $sale_payment->paid_amount;
                }
            }
        }
    }
@endphp

<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">Register Details ( 
        {{ Carbon::createFromFormat('Y-m-d H:i:s', $activeCashRegister->created_at)->format('jS M, Y h:i A') }}  
        - {{ Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('jS M, Y h:i A') }} )
    </h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
        class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <table class="cash_register_table table modal-table table-sm">
        <tbody>
            <tr>
                <td class="text-start">Cash In Hand :</td>
                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ $cash_in_hand }}</td>
            </tr>
    
            <tr>
                <td class="text-start">Cash Payment :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                    {{ number_format((float)$cash_payment, 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td class="text-start">Card Payment :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                    {{ number_format((float)$card_payment, 2, '.', '') }}
                </td>
            </tr>
    
            <tr>
                <td class="text-start">Cheque Payment :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                    {{ number_format((float)$cheque_payment, 2, '.', '') }}
                </td>
            </tr>
    
            <tr>
                <td class="text-start">Bank Transfer :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                    {{ number_format((float)$bank_payment, 2, '.', '') }}
                </td>
            </tr>
    
            <tr>
                <td class="text-start">Custom Payment :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                    {{ number_format((float)$custom_payment, 2, '.', '') }}
                </td>
            </tr>
    
            <tr>
                <td class="text-start">Other Payments :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ number_format((float)$other_payment, 2, '.', '') }}
                </td>
            </tr>

            <tr>
                <td class="text-start">Advanced Payments :</td>
                <td class="text-start">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ number_format((float)$advanced_payment, 2, '.', '') }}
                </td>
            </tr>
    
            <tr class="bg-info">
                <td class="text-start text-white"><b>Total Payment :</b></td>
                <td class="text-start text-white">
                    <b>
                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                        {{ number_format((float)$total_paid, 2, '.', '') }}
                    </b>
                </td>
            </tr>
    
            <tr class="bg-danger">
                <td class="text-start text-white"><b>Credit Sales :</b></td>
                <td class="text-start text-white">
                    <b>
                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                        {{ number_format((float)$total_due, 2, '.', '') }}
                    </b> 
                </td>
            </tr>
    
            <tr class="bg-info">
                <td class="text-start text-white"><b>Total Sales :</b></td>
                <td class="text-start text-white">
                    <b>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ number_format((float)$total_sale, 2, '.', '') }}
                    </b>
                </td>
            </tr>
        </tbody>
    </table>
    <hr>

    <div class="cash_register_info">
        <ul class="list-unstyled">
            <li>
                <b>User : </b> {{ $activeCashRegister->admin->prefix.' '.$activeCashRegister->admin->name.' '.$activeCashRegister->admin->last_name }}
            </li>

            <li>
                <b>Email : </b> {{ $activeCashRegister->admin->email }}
            </li>

            <li>
                <b>Branch : </b> {!! $activeCashRegister->branch ? $activeCashRegister->branch->name.' - '.$activeCashRegister->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (<b>Head Office</b>)' !!}
            </li>

            <li>
                <b>Cash Counter : </b> {!! $activeCashRegister->cash_counter ? $activeCashRegister->cash_counter->counter_name.' (<b>'.$activeCashRegister->cash_counter->short_name.'</b>)' : 'N/A' !!}
            </li>
        </ul>
    </div>
    
    <div class="form-group text-end mt-3">
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
    </div>
</div>