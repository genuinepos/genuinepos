@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<div class="sale_payment_print_area">
    <div class="header_area d-none">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($customerPayment->branch)
                        {{ $customerPayment->branch->name . '/' . $customerPayment->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                    @endif
                </b>
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
        <p><b>Title :</b>
            {{ $customerPayment->type == 1 ? 'Customer Payment' : 'Customer Return Payment' }} 
        </p>
        <p><b>Customer :</b> {{ $customerPayment->customer->name }}</p>
        <p><b>Phone :</b> {{ $customerPayment->customer->phone }}</p>
        <p><b>Address :</b> {{ $customerPayment->customer->address }}</p>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Paid Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td width="50%" class="text-start">
                                {{ App\Utils\Converter::format_in_bdt($customerPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Account :</th>
                            <td width="50%" class="text-start">{{ $customerPayment->account ? $customerPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Method :</th>
                            <td width="50%" class="text-start">{{ $customerPayment->paymentMethod ? $customerPayment->paymentMethod->name : $customerPayment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Voucher No :</th>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%" class="text-start">
                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($customerPayment->date)) . ' ' . date($timeFormat, strtotime($customerPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Note :</th>
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
                <p><b>PAYMENT AGAINST INVOICES :</b></p>
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
                    @foreach ($customerPayment->customer_payment_invoices as $pi)
                        <tr>
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->sale->date)) }}</td>
                            <td class="text-start">{{ $pi->sale->invoice_id }}</h6></td>
                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ $pi->paid_amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
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