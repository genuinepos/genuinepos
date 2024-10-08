@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $invoiceLayout = $sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value ? $generalSettings['add_sale_invoice_layout'] : $generalSettings['pos_sale_invoice_layout'];
@endphp

@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value)
    <style>
        @media print {
            /* *{
                font-family: 'Arial!important';
            } */

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
            margin-left: 20px;
            margin-right: 20px;
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

    <div class="print_delivery_note">
        <div class="details_area">
            @if ($invoiceLayout->is_header_less == 0)
                <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                    <div class="col-4">
                        @if ($sale->branch)

                            @if ($sale?->branch?->parent_branch_id)

                                @if ($sale->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $sale->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($sale->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $sale->branch?->name }}</span>
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
                        <p style="text-transform: uppercase;font-size:11px!important;" class="p-0 m-0 fw-bold">
                            @if ($sale?->branch)
                                @if ($sale?->branch?->parent_branch_id)
                                    {{ $sale?->branch?->parentBranch?->name }}
                                @else
                                    {{ $sale?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </p>

                        <p style="font-size:10px!important;">
                            @if ($sale?->branch)
                                {{ $sale->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p style="font-size:10px!important;">
                            @php
                                $email = $sale?->branch ? $sale?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $sale?->branch ? $sale?->branch?->phone : $generalSettings['business_or_shop__phone'];
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
                        <h6 style="text-transform: uppercase;"><strong>{{ $invoiceLayout->delivery_note_heading }}</strong></h6>
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
                            <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Customer') }} : </span>
                                {{ $sale?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $sale?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $sale?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:10px!important;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $sale?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ $invoiceLayout->delivery_note_heading }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Invoice No') }} : </span> {{ $sale->invoice_id }}
                        </li>

                        @if (isset($sale->salesOrder))
                            <li style="font-size:10px!important;">
                                <span class="fw-bold">{{ __('Order ID') }} : </span> {{ $sale?->salesOrder->order_id }}
                            </li>

                            <li style="font-size:10px!important;">
                                <span class="fw-bold">{{ __('Reference') }} : </span> {{ $sale?->reference }}
                            </li>
                        @endif

                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat, strtotime($sale->date)) . ' ' . $sale->time }}
                        </li>

                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-2 pb-2">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('S/L') }}</th>
                            <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Description') }}</th>
                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Quantity') }}</th>
                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Unit') }}</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size:10px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:10px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->variant_id)
                                        -{{ $saleProduct->variant_name }}
                                    @endif
                                    @php
                                        $productCode = $saleProduct->variant_code ? $saleProduct->variant_code : $saleProduct->product_code;
                                    @endphp

                                    {!! $invoiceLayout->product_code == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' : '' !!}

                                    {!! isset($saleProduct->description) ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . $saleProduct->description . '</span>' : '' !!}

                                    {!! $invoiceLayout->product_details == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . Str::limit($saleProduct->product_details, 1000, '...') . '</span>' : '' !!}
                                </td>

                                <td class="text-end" style="font-size:10px!important;">{{ $saleProduct->quantity }}</td>
                                <td class="text-end" style="font-size:10px!important;">{{ $saleProduct->unit_code_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- @if (count($customerCopySaleProducts) > 15)
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
            @endif --}}

            <div class="row">
                <div class="col-12">
                    <div>
                        <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Note') }} : </span> {{ $sale?->note }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Ship. Address') }} : </span> {{ $sale?->shipment_address }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Deliver To') }} : </span> {{ $sale?->delivered_to }}</p>
                    </div>
                </div>
            </div>

            <br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p class="text-uppercase borderTop fw-bold">{{ __("Customer's Signature") }}</p>
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
            /* *{
                font-family: 'Arial!important';
            } */

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
            size: 5.8in 8.3in;
            margin-top: 0.8cm;
            margin-bottom: 35px;
            margin-left: 20px;
            margin-right: 20px;
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

    <div class="print_delivery_note">
        <div class="details_area">
            @if ($invoiceLayout->is_header_less == 0)
                <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                    <div class="col-4">
                        @if ($sale->branch)

                            @if ($sale?->branch?->parent_branch_id)

                                @if ($sale->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $sale->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($sale->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;text-transform:uppercase;">{{ $sale->branch?->name }}</span>
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
                        <p style="text-transform: uppercase; font-size:9px;" class="p-0 m-0 fw-bold">
                            @if ($sale?->branch)
                                @if ($sale?->branch?->parent_branch_id)
                                    {{ $sale?->branch?->parentBranch?->name }}
                                @else
                                    {{ $sale?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @if ($sale?->branch)
                                {{ $sale->branch->address . ', ' }}
                                {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p style="font-size:9px;">
                            @php
                                $email = $sale?->branch ? $sale?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $sale?->branch ? $sale?->branch?->phone : $generalSettings['business_or_shop__phone'];
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
                        <h6 style="text-transform: uppercase;">{{ $invoiceLayout->delivery_note_heading }}</h6>
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
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Customer') }} : </span>{{ $sale?->customer?->name }}</li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }} : </span>{{ $sale?->customer?->address }}</li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $sale?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $sale?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ $invoiceLayout->delivery_note_heading }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Invoice No') }} : </span> {{ $sale->invoice_id }}</li>

                        @if (isset($sale->salesOrder))
                            <li style="font-size:9px!important; line-height:1.5;">
                                <span class="fw-bold">{{ __('Order ID') }} : </span> {{ $sale?->salesOrder?->order_id }}
                            </li>

                            <li style="font-size:9px!important;">
                                <span class="fw-bold">{{ __('Reference') }} : </span> {{ $sale?->reference }}
                            </li>
                        @endif

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat, strtotime($sale->date)) . ' ' . $sale->time }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
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
                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Unit') }}</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->variant_id)
                                        -{{ $saleProduct->variant_name }}
                                    @endif

                                    @php
                                        $productCode = $saleProduct->variant_code ? $saleProduct->variant_code : $saleProduct->product_code;
                                    @endphp

                                    {!! $invoiceLayout->product_code == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' : '' !!}

                                    {!! isset($saleProduct->description) ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . $saleProduct->description . '</span>' : '' !!}

                                    {!! $invoiceLayout->product_details == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . Str::limit($saleProduct->product_details, 1000, '...') . '</span>' : '' !!}
                                </td>

                                <td class="text-end" style="font-size:9px!important;">{{ $saleProduct->quantity }}</td>
                                <td class="text-end" style="font-size:9px!important;">{{ $saleProduct->unit_code_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- @if (count($customerCopySaleProducts) > 15)
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
            @endif --}}

            <div class="row">
                <div class="col-12">
                    <div class="bank_details">
                        <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Note') }} : </span> {{ $sale?->note }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Ship. Address') }} : </span> {{ $sale?->shipment_address }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Deliver To') }} : </span> {{ $sale?->delivered_to }}</p>
                    </div>
                </div>
            </div>

            <br><br>

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

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($dateFormat) }}</small>
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
