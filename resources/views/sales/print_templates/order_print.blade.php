@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $invoiceLayout = $generalSettings['add_sale_invoice_layout'];
    $__receivedAmount = isset($receivedAmount) ? $receivedAmount : 0;
@endphp

<!-- Sales Order print templete-->
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

    <div class="sale_print_template">
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
                        @if ($order->branch)

                            @if ($order?->branch?->parent_branch_id)

                                @if ($order->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($order->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->name }}</span>
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
                        <p style="text-transform: uppercase;" class="p-0 m-0">
                            <strong>
                                @if ($order?->branch)
                                    @if ($order?->branch?->parent_branch_id)
                                        {{ $order?->branch?->parentBranch?->name }}
                                    @else
                                        {{ $order?->branch?->name }}
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            </strong>
                        </p>

                        <p>
                            @if ($order?->branch)
                                {{ $invoiceLayout->branch_city == 1 ? $order->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $order->branch->state . ', ' : '' }}
                                {{ $order->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $order->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $order->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p>
                            @php
                                $email = $order?->branch ? $order?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $order?->branch ? $order?->branch?->phone : $generalSettings['business_or_shop__phone'];
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
                        <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Sales Order') }}</h5>
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
                                {{ $order?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $order?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $order?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $order?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5 style="text-transform: uppercase;">{{ __('Sales Order') }}</h5>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->order_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) . ' ' . $order->time }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Order ID') }} : </span> {{ $order->order_id }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $order?->createdBy?->prefix . ' ' . $order?->createdBy?->name . ' ' . $order?->createdBy?->last_name }}
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
                        @foreach ($customerCopySaleProducts as $orderProduct)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $orderProduct->p_name }}

                                    @if ($orderProduct->variant_id)
                                        -{{ $orderProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $orderProduct->description . '</small>' : '' !!}
                                </td>

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start" style="font-size:11px!important;">
                                        @if ($orderProduct->warranty_id)
                                            {{ $orderProduct->w_duration . ' ' . $orderProduct->w_duration_type }}
                                            {{ $orderProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $orderProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <strong>{{ __('No') }}</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ $orderProduct->quantity }}/{{ $orderProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_inc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:11px!important;">
                                        ({{ $orderProduct->unit_tax_percent }}%)
                                        ={{ $orderProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($orderProduct->subtotal) }}
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
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Order Discount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($order->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                    @else
                                        ({{ $order->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Order Tax') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    ({{ $order->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($order->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Shipment Charge') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Ordered Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Advance Received') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $order->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due (On Order)') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Current Balance') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt(0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p class="text-uppercase borderTop fw-bold"><span>{{ __("Customer's Signature") }}</span></p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop fw-bold">{{ __('Prepared By') }}</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-end">
                        <p class="text-uppercase borderTop fw-bold">{{ __('Authorized By') }}</p>
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
            size: 5.8in 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
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

    <div class="sale_print_template">
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
                        @if ($order->branch)

                            @if ($order?->branch?->parent_branch_id)

                                @if ($order->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($order->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $order->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $order->branch?->name }}</span>
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
                        <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
                            @if ($order?->branch)
                                @if ($order?->branch?->parent_branch_id)
                                    {{ $order?->branch?->parentBranch?->name }}
                                @else
                                    {{ $order?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @if ($order?->branch)
                                {{ $order->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_city == 1 ? $order->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $order->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $order->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $order->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @php
                                $email = $order?->branch?->email ? $order?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $order?->branch?->phone ? $order?->branch?->phone : $generalSettings['business_or_shop__phone'];
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
                        <h5 class="fw-bold" style="text-transform: uppercase;">{{ __('Sales Order') }}</h5>
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
                                {{ $order?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $order?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $order?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $order?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ __('Sales Order') }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->order_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) . ' ' . $order->time }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Order ID') }} : </span> {{ $order->order_id }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $order?->createdBy?->prefix . ' ' . $order?->createdBy?->name . ' ' . $order?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Description') }}</th>

                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Warranty') }}</th>
                            @endif

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
                        @foreach ($customerCopySaleProducts as $orderProduct)
                            <tr>
                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $orderProduct->p_name }}

                                    @if ($orderProduct->variant_id)
                                        -{{ $orderProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $orderProduct->description . '</small>' : '' !!}
                                </td>

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start" style="font-size:9px!important;">
                                        @if ($orderProduct->warranty_id)
                                            {{ $orderProduct->w_duration . ' ' . $orderProduct->w_duration_type }}
                                            {{ $orderProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $orderProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <strong>{{ __('No') }}</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:9px!important;">{{ $orderProduct->quantity }}/{{ $orderProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_inc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:9px!important;">
                                        ({{ $orderProduct->unit_tax_percent }}%)
                                        ={{ $orderProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($orderProduct->subtotal) }}
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
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">{{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Order Discount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($order->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                    @else
                                        ({{ $order->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Order Tax') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    ({{ $order->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($order->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Shipment Charge') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Ordered Amount') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Advance Received') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $order->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Order)') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($order->due) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $order?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt(0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p class="text-uppercase borderTop fw-bold" style="font-size:10px!important;">{{ __("Customer's Signature") }}</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop fw-bold" style="font-size:10px!important;">{{ __('Prepared By') }}</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-end">
                        <p class="text-uppercase borderTop fw-bold" style="font-size:10px!important;">{{ __('Authorized By') }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 10px!important;" style="font-size:10px!important;">{{ $invoiceLayout->footer_text }}</p>
                </div>
            </div><br>

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
    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $order->total_invoice_amount }}"));
</script>
