<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
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
            
            <p>
                @if ($payment->sale->branch)
                    {{ $payment->sale->branch->city . ', ' . $payment->sale->branch->state . ', ' . $payment->sale->branch->zip_code . ', ' . $payment->sale->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <h6 style="margin-top: 10px;"><b>Payment Details</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <p><b>Title :</b>
            {{ $payment->payment_type == 1 ? 'Sale Payment' : 'Sale Return Payment' }} </p>
        <p><b>Invoice No :</b> {{ $payment->sale->invoice_id }}</p>
        <p><b>Customer :</b>
            {{ $payment->sale->customer ? $payment->sale->customer->name : 'Walk-In-Customer' }}</p>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Paid Amount :</th>
                            <td width="50%">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}
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
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Voucher No :</th>
                            <td width="50%">
                                {{ $payment->invoice_id }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%">
                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) . ' ' . date($timeFormat, strtotime($payment->time)) }}
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

    <div class="signature_area pt-5 mt-5 d-none">
        <br>
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">Signature Of Authority</th>
                    <th width="50%" class="text-end">Signature Of Receiver</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->invoice_id , $generator::TYPE_CODE_128)) }}">
                        <p>{{ $payment->invoice_id }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">Software by SpeedDigit Pvt. Ltd.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

