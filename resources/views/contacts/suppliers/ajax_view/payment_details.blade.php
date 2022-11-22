@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<div class="sale_payment_print_area">
    <div class="header_area d-hide">
        <div class="company_name text-center">
            <h3>
                <strong>
                    @if ($supplierPayment->branch)
                    
                        {{ $supplierPayment->branch->name . '/' . $supplierPayment->branch->branch_code }}
                    @else

                        {{ json_decode($generalSettings->business, true)['shop_name'] }} 
                    @endif
                </strong>
            </h3>
            <h6>
                @if ($supplierPayment->branch)
                    {{ $supplierPayment->branch->city . ', ' . $supplierPayment->branch->state . ', ' . $supplierPayment->branch->zip_code . ', ' . $supplierPayment->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </h6>
            <h6>Payment Details</h6>
        </div>
    </div>

    <div class="reference_area">
        <p><strong>Title :</strong>
            {{ $supplierPayment->type == 1 ? 'Supplier Payment' : 'Return Payment' }} 
        </p>
        <p><strong>Supplier :</strong> {{ $supplierPayment->supplier->name }}</p>
        <p><strong>Phone :</strong> {{ $supplierPayment->supplier->phone }}</p>
        <p><strong>Address :</strong> {{ $supplierPayment->supplier->address }}</p>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>Paid Amount :</strong></td>
                            <td width="50%" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($supplierPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Credit Account :</strong></td>
                            <td width="50%" class="text-start">{{ $supplierPayment->account ? $supplierPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Payment Method :</strong></td>
                            <td width="50%" class="text-start">{{ $supplierPayment->paymentMethod ? $supplierPayment->paymentMethod->name : $supplierPayment->pay_mode }}</td>
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
                                {{ $supplierPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Reference :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $supplierPayment->reference }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Paid On :</strong></td>
                            <td width="50%" class="text-start">

                                @php
                                    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp

                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($supplierPayment->date)) . ' ' . date($timeFormat, strtotime($supplierPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>Payment Note :</strong></td>
                            <td width="50%" class="text-start">
                                {{ $supplierPayment->note }}
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
                <p><b>DESTIBUTION OF DUE PURCHASES:</b></p>
            </div>
        </div>
        <div class="col-12">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">Purchase Date</th>
                        <th class="text-start">Purchase Invoice ID</th>
                        <th class="text-start">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_paid = 0;
                    @endphp
                    @foreach ($supplierPayment->supplier_payment_invoices as $pi)
                        <tr>
                            <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($pi->purchase->date)) }}</td>
                            <td class="text-start">{{ $pi->purchase->invoice_id }}</h6></td>
                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}</td>
                            @php
                                $total_paid += $pi->paid_amount;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total : </th>
                        <th class="text-start">{{ App\Utils\Converter::format_in_bdt($total_paid) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-hide">
        <br>
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">Signature Of Authority</th>
                    <th width="50%" class="text-end">Signature Of Receiver</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($supplierPayment->voucher_no, $generator::TYPE_CODE_128)) }}">
                        <p>{{ $supplierPayment->voucher_no }}</p>
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