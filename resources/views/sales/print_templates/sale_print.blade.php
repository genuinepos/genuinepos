@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $__receivedAmount = isset($receivedAmount) ? $receivedAmount : 0;

    $account = $sale?->customer;
    $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
    $branchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
    $__branchId = $account?->group?->sub_sub_group_number == 6 ? $branchId : '';
    $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: null, toDate: null, branchId: $__branchId);
    $invoiceLayout = $sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value ? $generalSettings['add_sale_invoice_layout'] : $generalSettings['pos_sale_invoice_layout'];
@endphp

<!-- Sale print templete-->
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
                        @if ($sale->branch)
                            @if ($sale?->branch?->parent_branch_id)

                                @if ($sale->branch?->parentBranch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($sale->branch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->name }}</span>
                                @endif
                            @endif
                        @else
                            @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-8 text-end">
                        <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
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

                        <p>
                            @if ($sale?->branch)
                                {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                            @else
                                {{ $generalSettings['business_or_shop__address'] }}
                            @endif
                        </p>

                        <p>
                            @php
                                $email = $sale?->branch ? $sale?->branch?->email : $generalSettings['business_or_shop__email'];
                                $phone = $sale?->branch ? $sale?->branch?->phone : $generalSettings['business_or_shop__phone'];
                            @endphp

                            @if ($invoiceLayout->branch_email)
                                <span class="fw-bold">{{ __('Email') }}</span> : {{ $email }},
                            @endif

                            @if ($invoiceLayout->branch_phone)
                                <span class="fw-bold">{{ __('Phone') }}</span> : {{ $phone }}
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 0)
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <h5 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h5>
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
                                {{ $sale?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Address') }} : </span>
                                {{ $sale?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Tax Number') }} : </span>
                                {{ $sale?->customer?->tax_number }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:11px!important;"><span class="fw-bold">{{ __('Phone') }} : </span> {{ $sale?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h5 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h5>
                            {{-- <h6>
                                @php
                                    $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                @endphp

                                @if ($sale->due <= 0)
                                    @lang('menu.paid')
                                @elseif ($sale->due > 0 && $sale->due < $payable)
                                    @lang('menu.partial')
                                @elseif($payable == $sale->due)
                                    @lang('menu.due')
                                @endif
                            </h6> --}}
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Invoice ID') }} : </span> {{ $sale->invoice_id }}
                        </li>

                        <li style="font-size:11px!important;">
                            <span class="fw-bold">{{ __('Created By') }} : </span> {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-2 pb-2">
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
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->variant_id)
                                        -{{ $saleProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start" style="font-size:11px!important;">
                                        @if ($saleProduct->warranty_id)
                                            {{ $saleProduct->w_duration . ' ' . $saleProduct->w_duration_type }}
                                            {{ $saleProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $saleProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <span class="fw-bold">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->quantity }}/{{ $saleProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:11px!important;">
                                        ({{ $saleProduct->unit_tax_percent }}%)
                                        ={{ $saleProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
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

                    @if ($invoiceLayout->account_name || $invoiceLayout->account_no || $invoiceLayout->bank_name || $invoiceLayout->bank_branch)
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($invoiceLayout->account_name)
                                <p style="font-size:11px!important;">{{ __('Account Name') }} : {{ $invoiceLayout->account_name }}</p>
                            @endif

                            @if ($invoiceLayout->account_no)
                                <p style="font-size:11px!important;">{{ __('Account No') }} : {{ $invoiceLayout->account_no }}</p>
                            @endif

                            @if ($invoiceLayout->bank_name)
                                <p style="font-size:11px!important;">{{ __('Bank') }} : {{ $invoiceLayout->bank_name }}</p>
                            @endif

                            @if ($invoiceLayout->bank_branch)
                                <p style="font-size:11px!important;">{{ __('Branch') }} : {{ $invoiceLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-6">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Net Total Amount') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    @if ($sale->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @else
                                        ({{ $sale->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    ({{ $sale->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Invoice Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Received Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>

                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Due (On Invoice)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:11px!important;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important;">
                                    {{ $amounts['closing_balance_in_flat_amount_string'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

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

            <br>
            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 10px!important;">{!! $invoiceLayout->invoice_notice ? '<span class="fw-bold">' . __('Attention') . ' : </span>' . $invoiceLayout->invoice_notice : '' !!}</p>
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
                        @if (env('PRINT_SD_SALE') == true)
                            <small style="font-size: 9px!important;" class="d-block">{{ __('Powered By') }} <span class="fw-bold">@lang('SpeedDigit Software Solution').</span></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif ($printPageSize == \App\Enums\PrintPageSize::AFivePage->value)
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

    <div class="print_modal_details">
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
                        @if ($sale->branch)

                            @if ($sale?->branch?->parent_branch_id)

                                @if ($sale->branch?->parentBranch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->parentBranch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->parentBranch?->name }}</span>
                                @endif
                            @else
                                @if ($sale->branch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                    <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->name }}</span>
                                @endif
                            @endif
                        @else
                            @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-8 text-end">
                        <p style="text-transform: uppercase;font-size:9px;" class="p-0 m-0 fw-bold">
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
                        <h6 style="text-transform: uppercase;" class="fw-bold">{{ $invoiceLayout->invoice_heading }}</h6>
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
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Customer') }}</span> : {{ $sale?->customer?->name }}</li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Address') }}</span> : {{ $sale?->customer?->address }}</li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Tax Number') }}</span> : {{ $sale?->customer?->tax_number }}</li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Phone') }}</span> : {{ $sale?->customer?->phone }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Invoice ID') }}</span> : {{ $sale->invoice_id }}
                        </li>

                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }}</span> : {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}</li>

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Created By') }}</span> : {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-2">
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
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start" style="font-size:9px!important;">{{ $loop->index + 1 }}</td>
                                <td class="text-start" style="font-size:9px!important;">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->variant_id)
                                        -{{ $saleProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start" style="font-size:9px!important;">
                                        @if ($saleProduct->warranty_id)
                                            {{ $saleProduct->w_duration . ' ' . $saleProduct->w_duration_type }}
                                            {{ $saleProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $saleProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <span class="fw-bold">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:9px!important;">{{ $saleProduct->quantity }}/{{ $saleProduct->unit_code_name }}</td>

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:9px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:9px!important;">
                                        ({{ $saleProduct->unit_tax_percent }}%)
                                        ={{ $saleProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>

                                <td class="text-end" style="font-size:9px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
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

                    @if ($invoiceLayout->account_name || $invoiceLayout->account_no || $invoiceLayout->bank_name || $invoiceLayout->bank_branch)
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($invoiceLayout->account_name)
                                <p style="font-size:9px!important;">{{ __('Account Name') }} : {{ $invoiceLayout->account_name }}</p>
                            @endif

                            @if ($invoiceLayout->account_no)
                                <p style="font-size:9px!important;">{{ __('Account No') }} : {{ $invoiceLayout->account_no }}</p>
                            @endif

                            @if ($invoiceLayout->bank_name)
                                <p style="font-size:9px!important;">{{ __('Bank') }} : {{ $invoiceLayout->bank_name }}</p>
                            @endif

                            @if ($invoiceLayout->bank_branch)
                                <p style="font-size:9px!important;">{{ __('Branch') }} : {{ $invoiceLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-6">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} :{{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($sale->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @else
                                        ({{ $sale->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    ({{ $sale->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Shipment Charge') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Invoice Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Received Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:11px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Invoice)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ $amounts['closing_balance_in_flat_amount_string'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p style="font-size:10px!important;" class="text-uppercase borderTop"><strong>{{ __("Customer's Signature") }}</strong></p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-center">
                        <p style="font-size:10px!important;" class="text-uppercase borderTop"><strong>{{ __('Prepared By') }}</strong></p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-end">
                        <p style="font-size:10px!important;" class="text-uppercase borderTop"><strong>{{ __('Authorized By') }}</strong></p>
                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 8px!important;">{!! $invoiceLayout->invoice_notice ? '<strong>' . __('Attention') . ' : </strong>' . $invoiceLayout->invoice_notice : '' !!}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <p style="font-size: 8px!important;">{{ $invoiceLayout->footer_text }}</p>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
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
@else
    <style>
        @page {
            margin: 8px;
        }
    </style>
    <!-- Packing slip print templete-->
    <div class="sale_print_template">
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($sale->branch)
                                        @if ($sale?->branch?->parent_branch_id)

                                            @if ($sale->branch?->parentBranch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->parentBranch?->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->parentBranch?->name }}</span>
                                            @endif
                                        @else
                                            @if ($sale->branch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->name }}</span>
                                            @endif
                                        @endif
                                    @else
                                        @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                            <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            @if ($sale?->branch)
                                <tr>
                                    <th class="text-center" style="font-size:11px;">
                                        <h6>
                                            @if ($sale?->branch?->parent_branch_id)
                                                {{ $sale?->branch?->parentBranch?->name }}
                                            @else
                                                {{ $sale?->branch?->name }}
                                            @endif
                                        </h6>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center" style="font-size:11px;">
                                        {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                        {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                                        {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                                        {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center" style="font-size:11px;">
                                            <span><b>{{ __('Email') }} : </b> {{ $sale->branch->email }}</span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center" style="font-size:11px;">
                                            <span><b>{{ __('Phone') }} : </b> {{ $sale->branch->phone }}</span>
                                        </th>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <th class="text-center" style="font-size:11px;">
                                        <span>{{ $generalSettings['business_or_shop__address'] }} </span>
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center" style="font-size:11px;">
                                            <span><b>{{ __('Phone') }} : </b> {{ $generalSettings['business_or_shop__phone'] }} </span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center" style="font-size:11px;">
                                            <span><b>{{ __('Email') }} : </b> {{ $generalSettings['business_or_shop__email'] }} </span>
                                        </th>
                                    </tr>
                                @endif
                            @endif
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size:11px;">
                                    <span>{{ __('Date') }} : {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center" style="font-size:11px;">
                                    <span>{{ __('Inv No.') }} : {{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center" style="font-size:11px;">
                                    <span>{{ __('Customer') }} : {{ $sale?->customer?->name }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th style="width: 50%;" class="text-start" style="font-size:11px;">{{ __('Description') }}</th>
                                <th class="text-center" style="font-size:9px;">{{ __('Qty') }}</th>
                                <th class="text-center" style="font-size:9px;">{{ __('Price') }}</th>
                                <th class="text-end" style="font-size:9px;">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant_id ? ' ' . $saleProduct->variant_name : '';
                                    @endphp
                                    <th style="width: 50%;" class="text-start" style="font-size:9px;">{{ $loop->index + 1 }}. {{ Str::limit($saleProduct->p_name, 25, '') . $variant }}</th>
                                    <th class="text-center" style="font-size:9px;">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center" style="font-size:9px;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end" style="font-size:9px;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Net Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Sale Discount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Total Invoice Amt.') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Received Amount') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $sale->paid) }}
                                    </span>
                                </th>
                            </tr>

                            @if ($changeAmount > 0 && $__receivedAmount > 0)
                                <tr>
                                    <th class="text-end" style="font-size:10px;">{{ __('Change') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <th class="text-end" style="font-size:10px;">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($changeAmount) }}
                                        </span>
                                    </th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Due (On Invoice)') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:10px;">{{ __('Current Balance') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:10px;">
                                    <span>
                                        {{ $amounts['closing_balance_in_flat_amount_string'] }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_text_area mt-2">
                    <table class="w-100 ">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <span>
                                        {{ $invoiceLayout->invoice_notice ? $invoiceLayout->invoice_notice : '' }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>
                                        {{ $invoiceLayout->footer_text ? $invoiceLayout->footer_text : '' }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_area mt-1">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            @if (env('PRINT_SD_SALE') == true)
                                <tr>
                                    <th class="text-center">
                                        <span>{{ __('Powered By') }} <b>{{ __('SpeedDigit Software Solution') }}.</b> </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Sale print templete end-->
@if ($printPageSize == \App\Enums\PrintPageSize::AFourPage->value || $printPageSize == \App\Enums\PrintPageSize::AFivePage->value)
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
        document.getElementById('inword').innerHTML = inWords(parseInt("{{ $sale->total_invoice_amount }}"));
    </script>
@endif
