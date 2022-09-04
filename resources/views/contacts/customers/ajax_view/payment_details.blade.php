@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<div class="sale_payment_print_area">
    <div class="header_area d-none">
        <div class="company_name text-center">
            <h3>
                <strong>
                    @if ($customerPayment->branch)

                        {{ $customerPayment->branch->name . '/' . $customerPayment->branch->branch_code }}
                    @else

                        {{ json_decode($generalSettings->business, true)['shop_name'] }} 
                    @endif
                </strong>
            </h3>
            <h6>
                @if ($customerPayment->branch)

                    {{ $customerPayment->branch->city . ', ' . $customerPayment->branch->state . ', ' . $customerPayment->branch->zip_code . ', ' . $customerPayment->branch->country }}
                @else

                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </h6>
            <h6>Payment Details</h6>
        </div>
    </div>

    <div class="reference_area">
        <p><strong>Title :</strong>
            {{ $customerPayment->type == 1 ? 'Customer Payment' : 'Customer Return Payment' }} 
        </p>
        <p><strong>Customer :</strong> {{ $customerPayment->customer->name }}</p>
        <p><strong>Phone :</strong> {{ $customerPayment->customer->phone }}</p>
        <p><strong>Address :</strong> {{ $customerPayment->customer->address }}</p>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start">
                                <strong>Paid Amount :</strong> {{ json_decode($generalSettings->business, true)['currency'] }}
                            </td>
                            
                            <td width="50%" class="text-start">
                                {{ App\Utils\Converter::format_in_bdt($customerPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Debit Account :</strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->account ? $customerPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong> Payment Method :</strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->paymentMethod ? $customerPayment->paymentMethod->name : $customerPayment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>Voucher No :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Reference :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->reference }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Paid On :</strong></td>
                            <td width="50%" class="text-start">
                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($customerPayment->date)) . ' ' . date($timeFormat, strtotime($customerPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Payment Note :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="heading_area">
                <p><b>{{ $customerPayment->type == 1 ? 'RECEIVED AGAINST INVOICES :' : 'PAYMENT AGAINST RETURN INVOICES :' }} </b></p>
            </div>
        </div>
        <div class="col-12">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">Sale Date</th>
                        <th class="text-start">Sale Invoice ID</th>
                        <th class="text-start">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($customerPayment->customer_payment_invoices as $pi)
                        @if ($pi->sale)
                            <tr>
                                <td class="text-start">
                                    {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->sale->date)) }}
                                </td>
                                <td class="text-start">{{ $pi->sale->invoice_id }}</h6></td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                    @php
                                        $total += $pi->paid_amount;
                                    @endphp
                                </td>
                            </tr>
                        @elseif($pi->sale_return)
                            <tr>
                                <td class="text-start">
                                    {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->sale_return->date)) }}
                                </td>
                                <td class="text-start">{{ $pi->sale_return->invoice_id }}</h6></td>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }} 
                                    {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                    @php
                                        $total += $pi->paid_amount;
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($total) }}</td>
                    </tr>
                </tfoot>
            </table>
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
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($customerPayment->voucher_no, $generator::TYPE_CODE_128)) }}">
                        <p>{{ $customerPayment->voucher_no }}</p>
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