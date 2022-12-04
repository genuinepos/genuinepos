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
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
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
            @if ($payment->is_advanced == 1)
                <b>@lang('menu.po_advance_payment')</b>
            @else 
                {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
            @endif
        </h6>
        <h6 class="text-navy-blue"><b>P.Invoice ID :</b> {{ $payment->purchase->invoice_id }}</h6>
        <h6 class="text-navy-blue"><b>@lang('menu.supplier')</b> {{ $payment->purchase->supplier->name }}</h6>
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
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Method :</th>
                            <td width="50%">
                                @if ($payment->paymentMethod)
                                    {{ $payment->paymentMethod->name }}
                                @else
                                    {{ $payment->pay_mode }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">@lang('menu.voucher_no') :</th>
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

    <div class="signature_area pt-5 mt-5 d-hide">
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
