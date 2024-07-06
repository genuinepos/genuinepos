@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $invoiceLayout = $generalSettings['add_sale_invoice_layout'];
@endphp

<!-- Draft print templete-->
@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value)
    <style>
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
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
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
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
        <div class="details_area">
            @if ($invoiceLayout->is_header_less == 0)
                <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                    <div class="col-4">
                        @if ($draft->branch)

                            @if ($draft?->branch?->parent_branch_id)

                                @if ($draft->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $draft->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $draft->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($draft->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $draft->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $draft->branch?->name }}</span>
                                @endif
                            @endif
                        @else
                            @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-8 text-end">
                        <p style="text-transform: uppercase;" class="p-0 m-0">
                            <strong>
                                @if ($draft?->branch)
                                    @if ($draft?->branch?->parent_branch_id)
                                        {{ $draft?->branch?->parentBranch?->name }}
                                    @else
                                        {{ $draft?->branch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </strong>
                        </p>

                        <p>
                            @if ($draft?->branch)
                                {{ $draft->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_city == 1 ? $draft->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $draft->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $draft->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $draft->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p>
                            @php
                                $email = $draft?->branch ? $draft?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $draft?->branch ? $draft?->branch?->phone : $generalSettings['business_or_shop__phone'];
                            @endphp

                            @if ($invoiceLayout->branch_email)
                                <span class="fw-bold">{{ __('Email') }} : </span> {{ $email }},
                            @endif

                            @if ($invoiceLayout->branch_phone)
                                <span class="fw-bold">{{ __('Phone') }} : </span> {{ $phone }}
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 0)
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Draft') }}</h5>
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
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Customer') }} : </span>
                                {{ $draft?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $draft?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $draft?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $draft?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5 style="text-transform: uppercase;">{{ __('Draft') }}</h5>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($draft->draft_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($generalSettings['business_or_shop__date_format'], strtotime($draft->date)) . ' ' . $draft->time }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Draft ID') }} : </span> {{ $draft->draft_id }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $draft?->createdBy?->prefix . ' ' . $draft?->createdBy?->name . ' ' . $draft?->createdBy?->last_name }}
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
                        @foreach ($customerCopySaleProducts as $draftProduct)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $draftProduct->p_name }}

                                    @if ($draftProduct->variant_id)
                                        -{{ $draftProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $draftProduct->description . '</small>' : '' !!}
                                </td>

                                <td class="text-end" style="font-size:11px!important;">{{ $draftProduct->quantity }}/{{ $draftProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_exc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($draftProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:11px!important;">
                                        ({{ $draftProduct->unit_tax_percent }}%)
                                        ={{ $draftProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draftProduct->subtotal) }}
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
                        <p style="text-transform: uppercase;" style="font-size:10px!important;"><span class="fw-bold">{{ __('Inword') }} : </span> <span id="inword"></span> {{ __('Only') }}.</p>
                    @endif
                </div>

                <div class="col-6">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><span class="fw-bold">{{ __('Net Total Amount') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</span></td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($draft->net_total_amount) }}</td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><span class="fw-bold"> {{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</span></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($draft->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($draft->order_discount_amount) }}
                                    @else
                                        ({{ $draft->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($draft->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><span class="fw-bold">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</span></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    ({{ $draft->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($draft->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><span class="fw-bold">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </span></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draft->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end" style="font-size:11px!important;"><span class="fw-bold">{{ __('Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </span></td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draft->total_invoice_amount) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop"><span class="fw-bold">{{ __('Prepared By') }}</span></p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop"><span class="fw-bold">{{ __('Authorized By') }}</span></p>
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
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __("M:") }} {{ config('speeddigit.phone') }}</small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>
        @media print {
            table {
                page-break-after: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @page {
            size: 5.8 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
        }

        div#footer {
            position: fixed;
            bottom: 22px;
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
        <div class="details_area">
            @if ($invoiceLayout->is_header_less == 0)
                <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                    <div class="col-4">
                        @if ($draft->branch)

                            @if ($draft?->branch?->parent_branch_id)

                                @if ($draft->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $draft->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $draft->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($draft->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'branch_logo/' . $draft->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $draft->branch?->name }}</span>
                                @endif
                            @endif
                        @else
                            @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                <img src="{{ Storage::disk('s3')->url(tenant('id') . '/' . 'business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-8 text-end">
                        <p style="text-transform: uppercase;uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
                            @if ($draft?->branch)
                                @if ($draft?->branch?->parent_branch_id)
                                    {{ $draft?->branch?->parentBranch?->name }}
                                @else
                                    {{ $draft?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @if ($draft?->branch)
                                {{ $draft->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_city == 1 ? $draft->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $draft->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $draft->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $draft->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @php
                                $email = $draft?->branch ? $draft?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $draft?->branch ? $draft?->branch?->phone : $generalSettings['business_or_shop__phone'];
                            @endphp

                            @if ($invoiceLayout->branch_email)
                                <span class="fw-bold">{{ __('Email') }} : </span> {{ $email }},
                            @endif

                            @if ($invoiceLayout->branch_phone)
                                <span class="fw-bold">{{ __('Phone') }} : </span> {{ $phone }}
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 0)
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <h6 class="fw-bold" style="text-transform: uppercase;">{{ __('Draft') }}</h6>
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
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Customer') }} : </span>
                                {{ $draft?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $draft?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $draft?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $draft?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ __('Draft') }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($draft->draft_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($generalSettings['business_or_shop__date_format'], strtotime($draft->date)) . ' ' . $draft->time }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Draft ID') }} : </span> {{ $draft->draft_id }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $draft?->createdBy?->prefix . ' ' . $draft?->createdBy?->name . ' ' . $draft?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-2 pb-2">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Description') }}</th>

                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Price (Exc. Tax)') }}</th>


                            @if ($invoiceLayout->product_discount)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Discount') }}</th>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            @endif

                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Price (Inc. Tax)') }}</th>

                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($customerCopySaleProducts as $draftProduct)
                            <tr>
                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $draftProduct->p_name }}

                                    @if ($draftProduct->variant_id)
                                        -{{ $draftProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $draftProduct->description . '</small>' : '' !!}
                                </td>

                                <td class="text-end" style="font-size:9px!important;">{{ $draftProduct->quantity }}/{{ $draftProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_exc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($draftProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:9px!important;">
                                        ({{ $draftProduct->unit_tax_percent }}%)
                                        ={{ $draftProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draftProduct->subtotal) }}
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
                        <p style="text-transform: uppercase;font-size:9px!important;"><span class="fw-bold">{{ __('Inword') }} : </span> <span id="inword"></span> {{ __('Only') }}.</p>
                    @endif
                </div>

                <div class="col-6">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important;">{{ __('Net Total Amount') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important;height:10px; line-height:10px;">{{ App\Utils\Converter::format_in_bdt($draft->net_total_amount) }}</td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important;">{{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important;height:10px; line-height:10px;">
                                    @if ($draft->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($draft->order_discount_amount) }}
                                    @else
                                        ({{ $draft->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($draft->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important;">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important;height:10px; line-height:10px;">
                                    ({{ $draft->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($draft->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important;">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draft->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important;">{{ __('Total Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($draft->total_invoice_amount) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop fw-bold" style="font-size:10px!important;">{{ __('Prepared By') }}</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop fw-bold" style="font-size:10px!important;">{{ __('Authorized By') }}</p>
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
                            <small style="font-size: 9px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __("M:") }} {{ config('speeddigit.phone') }}</small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
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

    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $draft->total_invoice_amount }}"));
</script>
