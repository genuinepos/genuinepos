@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $defaultLayout = DB::table('invoice_layouts')->where('branch_id', null)->where('is_default', 1)->first();
    $invoiceLayout = $holdInvoice?->branch?->branchSetting?->addSaleInvoiceLayout ? $holdInvoice?->branch?->branchSetting?->addSaleInvoiceLayout : $defaultLayout;
@endphp

<!-- Sale print templete-->
<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto;
            line-height: 1 !important;
            padding: 0px !important;
            margin: 0px !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
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
        @page {
            size: a4;
            margin-top: 0.8cm;
            /* margin-bottom: 35px;  */
            margin-left: 10px;
            margin-right: 10px;
        }

        div#footer {
            position: fixed;
            bottom: 25px;
            left: 0px;
            width: 100%;
            height: 0%;
            color: #CCC;
            background: #333;
            padding: 0;
            margin: 0;
        }
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
                    @if ($holdInvoice->branch)

                        @if ($holdInvoice?->branch?->parent_branch_id)

                            @if ($holdInvoice->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $holdInvoice->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $holdInvoice->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($holdInvoice->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $holdInvoice->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $holdInvoice->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                            <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;font-size:11px!important;" class="p-0 m-0">
                        <strong>
                            @if ($holdInvoice?->branch)
                                @if ($holdInvoice?->branch?->parent_branch_id)
                                    {{ $holdInvoice?->branch?->parentBranch?->name }}
                                @else
                                    {{ $holdInvoice?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p style="font-size:11px!important;">
                        @if ($holdInvoice?->branch)
                            {{ $holdInvoice->branch->address . ', ' }}
                            {{ $invoiceLayout->branch_city == 1 ? $holdInvoice->branch->city . ', ' : '' }}
                            {{ $invoiceLayout->branch_state == 1 ? $holdInvoice->branch->state . ', ' : '' }}
                            {{ $invoiceLayout->branch_zipcode == 1 ? $holdInvoice->branch->zip_code . ', ' : '' }}
                            {{ $invoiceLayout->branch_country == 1 ? $holdInvoice->branch->country : '' }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p style="font-size:11px!important;">
                        @php
                            $email = $holdInvoice?->branch ? $holdInvoice?->branch?->email : $generalSettings['business_or_shop__email'];
                            $phone = $holdInvoice?->branch ? $holdInvoice?->branch?->phone : $generalSettings['business_or_shop__phone'];
                        @endphp

                        @if ($invoiceLayout->branch_email)
                            <strong>{{ __('Email') }} : </strong> {{ $email }},
                        @endif

                        @if ($invoiceLayout->branch_phone)
                            <strong>{{ __('Phone') }} : </strong> {{ $phone }}
                        @endif
                    </p>
                </div>
            </div>
        @endif

        @if ($invoiceLayout->is_header_less == 0)
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h6 class="fw-bold" style="text-transform: uppercase;">{{ __('Hold Invoice') }}</h6>
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
                            {{ $holdInvoice?->customer?->name }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_address)
                        <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong>
                            {{ $holdInvoice?->customer?->address }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_tax_no)
                        <li style="font-size:11px!important;"><strong>{{ __('Tax Number') }} : </strong>
                            {{ $holdInvoice?->customer?->tax_number }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_phone)
                        <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong> {{ $holdInvoice?->customer?->phone }}</li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-4 text-center">
                @if ($invoiceLayout->is_header_less == 1)
                    <div class="middle_header_text text-center">
                        <h6 style="text-transform: uppercase;">{{ __('Hold Invoice') }}</h6>
                    </div>
                @endif

                <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($holdInvoice->hold_invoice_id, $generator::TYPE_CODE_128)) }}">
            </div>

            <div class="col-lg-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;">
                        <strong>{{ __('Date') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($holdInvoice->date)) }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Hold Invoice ID') }} : </strong> {{ $holdInvoice->hold_invoice_id }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Created By') }} : </strong> {{ $holdInvoice?->createdBy?->prefix . ' ' . $holdInvoice?->createdBy?->name . ' ' . $holdInvoice?->createdBy?->last_name }}
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

                        @if ($invoiceLayout->product_brand)
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Brand.') }}</th>
                        @endif

                        @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Warranty') }}</th>
                        @endif

                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Quantity') }}</th>

                        @if ($invoiceLayout->product_price_exc_tax)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Price (Exc. Tax)') }}</th>
                        @endif

                        @if ($invoiceLayout->product_discount)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Discount') }}</th>
                        @endif

                        @if ($invoiceLayout->product_tax)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                        @endif

                        @if ($invoiceLayout->product_price_inc_tax)
                            <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>
                        @endif

                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $holdInvoiceProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $holdInvoiceProduct->p_name }}

                                @if ($holdInvoiceProduct->variant_id)
                                    -{{ $holdInvoiceProduct->variant_name }}
                                @endif

                                @php
                                    $productCode = $holdInvoiceProduct->variant_code ? $holdInvoiceProduct->variant_code : $holdInvoiceProduct->product_code;
                                @endphp

                                {!! $invoiceLayout->product_code == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' : '' !!}

                                {!! isset($holdInvoiceProduct->description) ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . $holdInvoiceProduct->description . '</span>' : '' !!}

                                {!! $invoiceLayout->product_details == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . Str::limit($holdInvoiceProduct->product_details, 1000, '...') . '</span>' : '' !!}
                            </td>

                            @if ($invoiceLayout->product_brand)
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $holdInvoiceProduct->brand_name }}
                                </td>
                            @endif

                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <td class="text-start" style="font-size:11px!important;">
                                    @if ($holdInvoiceProduct->warranty_id)
                                        {{ $holdInvoiceProduct->w_duration . ' ' . $holdInvoiceProduct->w_duration_type }}
                                        {{ $holdInvoiceProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                        {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $holdInvoiceProduct->w_description . '</small>' : '' !!}
                                    @else
                                        <strong>{{ __('No') }}</strong>
                                    @endif
                                </td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">{{ $holdInvoiceProduct->quantity }}/{{ $holdInvoiceProduct->unit_code_name }}</td>

                            @if ($invoiceLayout->product_price_exc_tax)
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($holdInvoiceProduct->unit_price_exc_tax) }} </td>
                            @endif

                            @if ($invoiceLayout->product_discount)
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($holdInvoiceProduct->unit_discount_amount) }}
                                </td>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <td class="text-end" style="font-size:11px!important;">
                                    ({{ $holdInvoiceProduct->unit_tax_percent }}%)={{ $holdInvoiceProduct->unit_tax_amount }}
                                </td>
                            @endif

                            @if ($invoiceLayout->product_price_inc_tax)
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($holdInvoiceProduct->unit_price_inc_tax) }}</td>
                            @endif

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($holdInvoiceProduct->subtotal) }}
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
                    <h6><em>{{ __('Continued To This Next Page') }}....</em></h6>
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
                    <p style="text-transform: uppercase;" style="font-size:10px!important;"><strong>{{ __('Inword') }} : </strong> <span id="inword"></span> {{ __('Only') }}.</p>
                @endif

                <div class="bank_details mt-2">
                    <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Note') }} : </span> {{ $holdInvoice?->note }}</p>
                </div>

                <div class="bank_details mt-1">
                    <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Ship. Address') }} : </span> {{ $holdInvoice?->shipment_address }}</p>
                </div>
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Net Total Amount') }} : {{ $holdInvoice?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($holdInvoice->net_total_amount) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> {{ __('Sale Discount') }} : {{ $holdInvoice?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($holdInvoice->order_discount_type == 1)
                                    ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($holdInvoice->order_discount_amount) }}
                                @else
                                    ({{ $holdInvoice->order_discount }}%)
                                    ={{ App\Utils\Converter::format_in_bdt($holdInvoice->order_discount_amount) }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Sale Vat/Tax') }} : {{ $holdInvoice?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                ({{ $holdInvoice->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($holdInvoice->order_tax_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Shipment Charge') }} : {{ $holdInvoice?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($holdInvoice->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>{{ __('Total Amount') }} : {{ $holdInvoice?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($holdInvoice->total_invoice_amount) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-6">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __('Prepared By') }}</strong></p>
                </div>
            </div>

            <div class="col-6">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __('Authorized By') }}</strong></p>
                </div>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('speeddigit.show_app_info_in_print') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
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
    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $holdInvoice->total_invoice_amount }}"));
</script>
