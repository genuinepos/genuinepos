@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="sale_payment_print_area">
    <div class="header_area d-hide">
        <div class="company_name text-center">
            <h3>
                <strong>
                    @if ($supplierPayment->branch)
                        {{ $supplierPayment->branch->name . '/' . $supplierPayment->branch->branch_code }}
                    @else
                        {{ $generalSettings['business__business_name'] }}
                    @endif
                </strong>
            </h3>
            <h6>
                @if ($supplierPayment->branch)
                    {{ $supplierPayment->branch->city . ', ' . $supplierPayment->branch->state . ', ' . $supplierPayment->branch->zip_code . ', ' . $supplierPayment->branch->country }}
                @else
                    {{ $generalSettings['business__address'] }}
                @endif
            </h6>
            <h6>@lang('menu.payment_details')</h6>
        </div>
    </div>

    <div class="reference_area">
        <p><strong>@lang('menu.title') </strong>
            {{ $supplierPayment->type == 1 ? 'Supplier Payment' : 'Return Payment' }}
        </p>
        <p><strong>@lang('menu.supplier') </strong> {{ $supplierPayment->supplier->name }}</p>
        <p><strong>@lang('menu.phone') </strong> {{ $supplierPayment->supplier->phone }}</p>
        <p><strong>@lang('menu.address') </strong> {{ $supplierPayment->supplier->address }}</p>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.paid_amount') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $generalSettings['business__currency_symbol'] }}
                                {{ App\Utils\Converter::format_in_bdt($supplierPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.credit_account') </strong></td>
                            <td width="50%" class="text-start">{{ $supplierPayment->account ? $supplierPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.payment_method') </strong></td>
                            <td width="50%" class="text-start">{{ $supplierPayment->paymentMethod ? $supplierPayment->paymentMethod->name : $supplierPayment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.voucher_no') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $supplierPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.reference') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $supplierPayment->reference }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.paid_on') </strong></td>
                            <td width="50%" class="text-start">

                                @php
                                    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp

                                {{ date($generalSettings['business__date_format'], strtotime($supplierPayment->date)) . ' ' . date($timeFormat, strtotime($supplierPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.payment_note') </strong></td>
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
                <p><b>{{ __('DESTITUTION OF DUE PURCHASES') }}</b></p>
            </div>
        </div>
        <div class="col-12">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">@lang('menu.purchase_date')</th>
                        <th class="text-start">@lang('menu.purchase_invoice_id')</th>
                        <th class="text-start">@lang('menu.paid_amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_paid = 0;
                    @endphp
                    @foreach ($supplierPayment->supplier_payment_invoices as $pi)
                        <tr>
                            <td class="text-start">{{ date($generalSettings['business__date_format'], strtotime($pi->purchase->date)) }}</td>
                            <td class="text-start">{{ $pi->purchase->invoice_id }}</h6>
                            </td>
                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}</td>
                            @php
                                $total_paid += $pi->paid_amount;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">@lang('menu.total') </th>
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
                    <th width="50%">@lang('menu.signature_of_authority')</th>
                    <th width="50%" class="text-end">@lang('menu.signature_of_receiver')</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($supplierPayment->voucher_no, $generator::TYPE_CODE_128)) }}">
                        <p>{{ $supplierPayment->voucher_no }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">@lang('menu.software_by_speedDigit_pvt_ltd')</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
