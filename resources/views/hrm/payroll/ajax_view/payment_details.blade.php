<div class="payroll_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            @if ($payment->payroll->employee->branch)
                {{ $payment->payroll->employee->branch->name . '/' . $payment->payroll->employee->branch->branch_code }} <br>
                {{ $payment->payroll->employee->branch->city == 1 ? $payment->payroll->employee->branch->city : '' }},
                {{ $payment->payroll->employee->branch->state == 1 ? $payment->payroll->employee->branch->state : '' }},
                {{ $payment->payroll->employee->branch->zip_code == 1 ? $payment->payroll->employee->branch->zip_code : '' }},
                {{ $payment->payroll->employee->branch->country == 1 ? $payment->payroll->employee->branch->country : '' }}.
            @else
                <h6>{{json_decode($generalSettings->business, true)['shop_name']}}  (<b>Head Office</b>)</h6>
                <p>{{json_decode($generalSettings->business, true)['address']}} </p> 
                <p><b>Phone :</b>  {{json_decode($generalSettings->business, true)['phone']}} </p> 
            @endif
            <h6 class="modal-title" id="exampleModalLabel">Payroll Of
                <b>{{ $payment->payroll->employee->prefix . ' ' . $payment->payroll->employee->name . ' ' . $payment->payroll->employee->last_name }}</b>
                for <b>{{ $payment->payroll->month . ' ' . $payment->payroll->year }}</b>
            </h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <h6><b>Title :</b> Payroll Payment</h6>
        <h6><b>Reference No :</b> {{ $payment->payroll->reference_no }}</h6>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Paid Amount :</th>
                            <td width="50%" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $payment->paid }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Due :</th>
                            <td width="50%" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }} {{ $payment->due }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Method :</th>
                            <td width="50%" class="text-start">{{ $payment->pay_mode }}</td>
                        </tr>

                        @if ($payment->pay_mode == 'Card')
                            <tr>
                                <th width="50%" class="text-start">Card Number :</th>
                                <td width="50%" class="text-start">{{ $payment->card_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Holder :</th>
                                <td width="50%" class="text-start">{{ $payment->card_holder }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Card Type :</th>
                                <td width="50%" class="text-start">{{ $payment->card_type }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%" class="text-start">{{ $payment->card_transaction_no }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Month :</th>
                                <td width="50%" class="text-start">{{ $payment->card_month }}</td>
                            </tr>

                            <tr>
                                <th width="50%" class="text-start">Year :</th>
                                <td width="50%" class="text-start">{{ $payment->card_year }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Chaque')
                            <tr>
                                <th width="50%" class="text-start">Chaque No :</th>
                                <td width="50%" class="text-start">{{ $payment->cheque_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Band-Transter')
                            <tr>
                                <th width="50%" class="text-start">Account No :</th>
                                <td width="50%" class="text-start">{{ $payment->account_no }}</td>
                            </tr>
                        @elseif($payment->pay_mode == 'Custom')
                            <tr>
                                <th width="50%" class="text-start">Transaction No :</th>
                                <td width="50%" class="text-start">{{ $payment->transaction_no }}</td>
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
                            <td width="50%" class="text-start">
                                {{ $payment->reference_no }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%" class="text-start">
                                {{ $payment->date }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Note :</th>
                            <td width="50%" class="text-start">
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
                    <th width="50%">Signature Of Authority</th>
                    <th width="50%" class="text-end">Signature Of Receiver</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-navy-blue text-center">Developed by <b>SpeedDigit Pvt. Ltd.</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
