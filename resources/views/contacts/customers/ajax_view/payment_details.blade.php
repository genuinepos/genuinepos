<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    div#footer {
        position: fixed;
        bottom: 24px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 20px;
        margin-right: 20px;
    }
</style>
@php
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="sale_payment_print_area">
    <div class="header_area d-hide">
        <div class="company_name text-center">
            <div class="company_name text-center">
                <h4>
                    @if ($customerPayment->branch)

                        @if ($customerPayment->branch->logo)
                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'branch_logo/' . $customerPayment->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $customerPayment->branch->name }}</span>
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </h4>

                <p>
                    @if ($customerPayment->branch)
                        <p style="width: 60%; margin:0 auto;">{{ $customerPayment->branch->city . ', ' . $customerPayment->branch->state . ', ' . $customerPayment->branch->zip_code . ', ' . $customerPayment->branch->country }}</p>
                    @else
                        <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business_or_shop__address'] }}</p>
                    @endif
                </p>

                <h6 style="margin-top: 10px;">@lang('menu.payment_receive_voucher')</h6>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-6">
            <p><strong>@lang('menu.customer') </strong> {{ $customerPayment->customer->name }}</p>
            <p><strong>@lang('menu.phone') </strong> {{ $customerPayment->customer->phone }}</p>
            <p><strong>@lang('menu.address') </strong> {{ $customerPayment->customer->address }}</p>
        </div>

        <div class="col-6">
            <p><strong>@lang('menu.type') </strong>
                {{ $customerPayment->type == 1 ? 'Receive Payment' : 'Refund' }}
            </p>
        </div>
    </div>

    <div class="total_amount_table_area">
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start">
                                <strong>@lang('menu.paid_amount') </strong> {{ $generalSettings['business_or_shop__currency_symbol'] }}
                            </td>

                            <td width="50%" class="text-start">
                                {{ App\Utils\Converter::format_in_bdt($customerPayment->paid_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.debit_account') </strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->account ? $customerPayment->account->name : '' }}</td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong> @lang('menu.payment_method') </strong></td>
                            <td width="50%" class="text-start">{{ $customerPayment->paymentMethod ? $customerPayment->paymentMethod->name : $customerPayment->pay_mode }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.voucher_no') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->voucher_no }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.reference') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->reference }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.paid_on') </strong></td>
                            <td width="50%" class="text-start">
                                @php
                                    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
                                @endphp
                                {{ date($generalSettings['business_or_shop__date_format'], strtotime($customerPayment->date)) . ' ' . date($timeFormat, strtotime($customerPayment->time)) }}
                            </td>
                        </tr>

                        <tr>
                            <td width="50%" class="text-start"><strong>@lang('menu.payment_note') </strong></td>
                            <td width="50%" class="text-start">
                                {{ $customerPayment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (count($customerPayment->customer_payment_invoices))
        <div class="row">
            <div class="col-12">
                <div class="heading_area">
                    <p><strong>{{ $customerPayment->type == 1 ? 'RECEIVED AGAINST SALES/ORDERS:' : 'PAYMENT AGAINST RETURN INVOICES :' }} </strong></p>
                </div>
            </div>
            <div class="col-12">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('menu.sale_date')</th>
                            <th class="text-start">{{ __('Sale Invoice ID') }}</th>
                            <th class="text-start">@lang('menu.paid_amount')</th>
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
                                        {{ date($generalSettings['business_or_shop__date_format'], strtotime($pi->sale->date)) }}
                                    </td>
                                    <td class="text-start">{{ $pi->sale->invoice_id }}</h6>
                                    </td>
                                    <td class="text-start">
                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                        @php $total += $pi->paid_amount; @endphp
                                    </td>
                                </tr>
                            @elseif($pi->sale_return)
                                <tr>
                                    <td class="text-start">
                                        {{ date($generalSettings['business_or_shop__date_format'], strtotime($pi->sale_return->date)) }}
                                    </td>
                                    <td class="text-start">{{ $pi->sale_return->invoice_id }}</h6>
                                    </td>
                                    <td class="text-start">
                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        {{ App\Utils\Converter::format_in_bdt($pi->paid_amount) }}
                                        @php $total += $pi->paid_amount; @endphp
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">@lang('menu.total') </th>
                            <th class="text-start">{{ App\Utils\Converter::format_in_bdt($total) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    <div class="footer_area d-hide">
        <br><br>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.perceived_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;"> @lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($customerPayment->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $customerPayment->voucher_no }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (env('PRINT_SD_SALE') == true)
                        <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
