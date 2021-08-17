@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->purchase->branch)
                        {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                    @endif
                </b>
            </h3>
            
            <h6>
                @if ($payment->purchase->branch)
                    {{ $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code }}
                    (<b>Branch/Concern</b>) ,<br>
                    {{ $payment->purchase->branch ? $payment->purchase->branch->city : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->state : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->zip_code : '' }},
                    {{ $payment->purchase->branch ? $payment->purchase->branch->country : '' }}.
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }} <br>
                    <b>Phone</b> : {{ json_decode($generalSettings->business, true)['phone'] }} <br>
                    <b>Email</b> : {{ json_decode($generalSettings->business, true)['email'] }} <br>
                @endif
            </h6>
            <h6>Payment Details</h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6 class="text-navy-blue"><b>Title :</b>
            {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Receive Purchase Return' }} </h6>
        <h6 class="text-navy-blue"><b>P.Invoice ID :</b> {{ $payment->purchase->invoice_id }}</h6>
        <h6 class="text-navy-blue"><b>Supplier :</b> {{ $payment->purchase->supplier->name }}</h6>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th class="text-start" width="50%">Paid Amount :</th>
                            <td width="50%">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $payment->paid_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Method :</th>
                            <td width="50%">{{ $payment->pay_mode }}</td>
                        </tr>

                        @if ($payment->pay_mode == 'Card')
                            <tr>
                                <th width="50%" class="text-start">Card Number :</th>
                                <td width="50%">{{ $payment->card_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Holder :</th>
                                <td width="50%">{{ $payment->card_holder }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Type :</th>
                                <td width="50%">{{ $payment->card_type }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%">{{ $payment->card_transaction_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Month :</th>
                                <td width="50%">{{ $payment->card_month }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Year :</th>
                                <td width="50%">{{ $payment->card_year }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Cheque')
                            <tr>
                                <th width="50%" class="text-start">Chaque No :</th>
                                <td width="50%">{{ $payment->cheque_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Bank-Transfer')
                            <tr>
                                <th width="50%" class="text-start">Account No :</th>
                                <td width="50%">{{ $payment->account_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Custom')
                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%">{{ $payment->transaction_no }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Voucher No :</th>
                            <td width="50%">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date))  . ' ' . date($timeFormat, strtotime($payment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Note :</th>
                            <td width="50%">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    <div class="signature_area pt-5 mt-5 d-none">
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">Signature Of Authority</th>
                    <th width="50%" class="text-end">Signature Of Receiver</th>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">Software by <b>SpeedDigit Pvt. Ltd.</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
