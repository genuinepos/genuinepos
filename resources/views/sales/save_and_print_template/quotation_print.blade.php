@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $defaultLayout = DB::table('invoice_layouts')
        ->where('branch_id', null)
        ->where('is_default', 1)
        ->first();
    $invoiceLayout = $quotation?->branch?->branchSetting?->addSaleInvoiceLayout ? $quotation?->branch?->branchSetting?->addSaleInvoiceLayout : $defaultLayout;
@endphp

<!-- Sale print templete-->

<style>
    @media print {
        table { page-break-after: auto }

        tr { page-break-inside: avoid; page-break-after: auto }

        td { page-break-inside: avoid; page-break-after: auto }

        thead { display: table-header-group; }

        tfoot { display: table-footer-group; }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    div#footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }
</style>

<div class="sale_print_template">
    <style>
        @page { size: a4; margin-top: 0.8cm; /* margin-bottom: 35px;  */ margin-left: 10px; margin-right: 10px; }

        div#footer { position: fixed; bottom: 25px; left: 0px; width: 100%; height: 0%; color: #CCC; background: #333; padding: 0; margin: 0; }
    </style>
    <div class="details_area">
        @if ($invoiceLayout->is_header_less == 0)
            <div class="row">
                <div class="col-12 text-center">
                    <p>{{ $invoiceLayout->header_text }}</p>
                    <p>{{ $invoiceLayout->sub_heading_1 }}</p>
                    <p>{{ $invoiceLayout->sub_heading_2 }}</p>
                    <p>{{ $invoiceLayout->sub_heading_3 }}</p>
                </div>
            </div>

            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($quotation->branch)

                        @if ($quotation?->branch?->parent_branch_id)

                            @if ($quotation->branch?->parentBranch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $quotation->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $quotation->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($quotation->branch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $quotation->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $quotation->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0">
                        <strong>
                            @if ($quotation?->branch)
                                @if ($quotation?->branch?->parent_branch_id)
                                    {{ $quotation?->branch?->parentBranch?->name }}
                                @else
                                    {{ $quotation?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business__shop_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p>
                        @if ($quotation?->branch)
                            {{ $invoiceLayout->branch_city == 1 ? $quotation->branch->city . ', ' : '' }}
                            {{ $invoiceLayout->branch_state == 1 ? $quotation->branch->state . ', ' : '' }}
                            {{ $invoiceLayout->branch_zipcode == 1 ? $quotation->branch->zip_code . ', ' : '' }}
                            {{ $invoiceLayout->branch_country == 1 ? $quotation->branch->country : '' }}
                        @else
                            {{ $generalSettings['business__address'] }}
                        @endif
                    </p>

                    <p>
                        @php
                            $email = $quotation?->branch?->email ? $quotation?->branch?->email : $generalSettings['business__email'];
                            $phone = $quotation?->branch?->phone ? $quotation?->branch?->phone : $generalSettings['business__phone'];
                        @endphp

                        @if ($invoiceLayout->branch_email)
                            <strong>{{ __('Email') }} : </strong> <b>{{ $email }}</b>,
                        @endif

                        @if ($invoiceLayout->branch_phone)
                            <strong>{{ __('Phone') }} : </strong> <b>{{ $phone }}</b>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        @if ($invoiceLayout->is_header_less == 0)
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;"><strong>{{ $invoiceLayout->quotation_heading }}</strong></h5>
                </div>
            </div>
        @endif

        @if ($invoiceLayout->is_header_less == 1)
            @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                <br />
            @endfor
        @endif

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    @if ($invoiceLayout->customer_name)
                        <li style="font-size:11px!important;"><strong>{{ __('Customer') }} : </strong>
                            {{ $quotation?->customer?->name }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_address)
                        <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong>
                            {{ $quotation?->customer?->address }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_tax_no)
                        <li style="font-size:11px!important;"><strong>{{ __('Tax Number') }} : </strong>
                            {{ $quotation?->customer?->tax_number }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_phone)
                        <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong> {{ $quotation?->customer?->phone }}</li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-4 text-center">
                @if ($invoiceLayout->is_header_less == 1)
                    <div class="middle_header_text text-center">
                        <h5 style="text-transform: uppercase;">{{ $invoiceLayout->quotation_heading }}</h5>
                        {{-- <h6>
                                @php
                                    $payable = $quotation->total_payable_amount - $quotation->sale_return_amount;
                                @endphp

                                @if ($quotation->due <= 0)
                                    @lang('menu.paid')
                                @elseif ($quotation->due > 0 && $quotation->due < $payable)
                                    @lang('menu.partial')
                                @elseif($payable == $quotation->due)
                                    @lang('menu.due')
                                @endif
                            </h6> --}}
                    </div>
                @endif

                <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($quotation->quotation_id, $generator::TYPE_CODE_128)) }}">
            </div>

            <div class="col-lg-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;">
                        <strong>{{ __('Date') }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($quotation->date)) }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Quotation ID') }} : </strong> {{ $quotation->quotation_id }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Created By') }} : </strong> {{ $quotation?->createdBy?->prefix . ' ' . $quotation?->createdBy?->name . ' ' . $quotation?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table print-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Description') }}</th>

                        @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Warranty') }}</th>
                        @endif

                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Price (Exc. Tax)') }}</th>


                        @if ($invoiceLayout->product_discount)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Discount') }}</th>
                        @endif

                        @if ($invoiceLayout->product_tax)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                        @endif

                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>

                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $quotationProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $quotationProduct->p_name }}

                                @if ($quotationProduct->variant_id)
                                    -{{ $quotationProduct->variant_name }}
                                @endif
                                {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $quotationProduct->description . '</small>' : '' !!}
                            </td>

                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <td class="text-start" style="font-size:11px!important;">
                                    @if ($quotationProduct->warranty_id)
                                        {{ $quotationProduct->w_duration . ' ' . $quotationProduct->w_duration_type }}
                                        {{ $quotationProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                        {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $quotationProduct->w_description . '</small>' : '' !!}
                                    @else
                                        <strong>{{ __('No') }}</strong>
                                    @endif
                                </td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">{{ $quotationProduct->quantity }}/{{ $quotationProduct->unit_code_name }}</td>

                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_exc_tax) }} </td>

                            @if ($invoiceLayout->product_discount)
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_discount_amount) }}
                                </td>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <td class="text-end" style="font-size:11px!important;">
                                    ({{ $quotationProduct->unit_tax_percent }}%)={{ $quotationProduct->unit_tax_amount }}
                                </td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_inc_tax) }}</td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotationProduct->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (count($customerCopySaleProducts) > 15)
            <br>
            <div class="row page_break">
                <div class="col-12 text-end">
                    <h6><em>@lang('menu.continued_to_this_next_page')....</em></h6>
                </div>
            </div>

            @if ($invoiceLayout->is_header_less == 1)
                @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                    <br />
                @endfor
            @endif
        @endif

        <div class="row">
            <div class="col-6">
                @if ($invoiceLayout->show_total_in_word == 1)
                    <p style="text-transform: uppercase;" style="font-size:10px!important;"><strong>{{ __("Inword") }} : </strong> <span id="inword"></span> {{ __('Only') }}.</p>
                @endif
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Net Total Amount') }} :{{ $generalSettings['business__currency'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->net_total_amount) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> {{ __('Sale Discount') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($quotation->order_discount_type == 1)
                                    ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($quotation->order_discount_amount) }}
                                @else
                                    ({{ $quotation->order_discount }}%)
                                    ={{ App\Utils\Converter::format_in_bdt($quotation->order_discount_amount) }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Sale Tax') }} : {{ $generalSettings['business__currency'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                ({{ $quotation->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($quotation->order_tax_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Shipment Charge') }} : {{ $generalSettings['business__currency'] }} </strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotation->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Total Amount') }} : {{ $generalSettings['business__currency'] }} </strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($quotation->total_invoice_amount) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-4">
                <div class="details_area text-start">
                    <p class="text-uppercase borderTop"><strong>{{ __("Customer's Signature") }}</strong></p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __('Prepared By') }}</strong></p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-end">
                    <p class="text-uppercase borderTop"><strong>{{ __('Authorized By') }}</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <p style="font-size: 10px!important;">{{ $invoiceLayout->footer_text }}</p>
            </div>
        </div><br>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (env('PRINT_SD_SALE') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ __('Powered By') }} <strong>@lang('SpeedDigit Software Solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sale print templete end-->
<script>
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    function inWords(num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }
    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $quotation->total_invoice_amount }}"));
</script>
