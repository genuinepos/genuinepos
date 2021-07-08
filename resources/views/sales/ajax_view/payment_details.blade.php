<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->sale->branch)
                        {{ $payment->sale->branch->name . '/' . $payment->sale->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                    @endif
                </b>
            </h3>
            <h6>
                @if ($payment->sale->branch)
                    {{ $payment->sale->branch->city . ', ' . $payment->sale->branch->state . ', ' . $payment->sale->branch->zip_code . ', ' . $payment->sale->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </h6>
            <h6>Payment Details</h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6 class="text-navy-blue"><b>Title :</b>
            {{ $payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return Payment' }} </h6>
        <h6 class="text-navy-blue"><b>Invoice No :</b> {{ $payment->sale->invoice_id }}</h6>
        <h6 class="text-navy-blue"><b>Customer :</b>
            {{ $payment->sale->customer ? $payment->sale->customer->name : 'Walk-In-Customer' }}</h6>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Paid Amount :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $payment->paid_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Method :</th>
                            <td width="50%" class="text-navy-blue">{{ $payment->pay_mode }}</td>
                        </tr>

                        @if ($payment->pay_mode == 'Card')
                            <tr>
                                <th width="50%" class="text-start">Card Number :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Holder :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_holder }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Type :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_type }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_transaction_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Month :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_month }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Year :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->card_year }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Cheque')
                            <tr>
                                <th width="50%" class="text-start">Chaque No :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->cheque_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Bank-Transfer')
                            <tr>
                                <th width="50%" class="text-start">Account No :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->account_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Custom')
                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%" class="text-navy-blue">{{ $payment->transaction_no }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Referance No :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ $payment->date . ' ' . $payment->time }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Note :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-none">
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%" class="text-navy-blue">Signature Of Authority</th>
                    <th width="50%" class="text-navy-blue text-end">Signature Of Receiver</th>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-navy-blue text-center">Software by SpeedDigit Pvt. Ltd.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

