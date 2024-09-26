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

                                @if ($invoiceLayout->show_shop_logo == 1)
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
                        <p style="text-transform: uppercase;font-size:10px;" class="p-0 m-0 fw-bold">
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

                        <p style="font-size:10px;">
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

                        <p style="font-size:10px;">
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
                        <h6 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h6>
                        @php
                            $paymentStatus = '';
                            $receivable = $sale->total_invoice_amount - $sale->sale_return_amount;

                            if ($sale->due <= 0) {
                                $paymentStatus = __('Paid');
                            } elseif ($sale->due > 0 && $sale->due < $receivable) {
                                $paymentStatus = __('Partial');
                            } elseif ($receivable == $sale->due) {
                                $paymentStatus = __('Due');
                            }
                        @endphp
                        <p class="fw-bold" style="text-transform: uppercase;">{{ $paymentStatus }}</p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 1)
                @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                    <br />
                @endfor
            @endif

            <div class="row mt-1">
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

                <div class="col-lg-4 text-center">
                    @if ($invoiceLayout->is_header_less == 1)
                        <div class="middle_header_text text-center">
                            <h6 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h6>
                        </div>
                    @endif

                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Date') }} : </span> {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}
                        </li>

                        @if ($sale?->jobCard)
                            <li style="font-size:10px!important;">
                                <span class="fw-bold">{{ __('Job No.') }} : </span> {{ $sale?->jobCard?->job_no }}
                            </li>
                        @endif

                        <li style="font-size:10px!important;">
                            <span class="fw-bold">{{ __('Invoice ID') }} : </span> {{ $sale->invoice_id }}
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

                            @if ($invoiceLayout->product_brand)
                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Brand.') }}</th>
                            @endif

                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <th class="fw-bold text-start" style="font-size:10px!important;">{{ __('Warranty') }}</th>
                            @endif

                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Quantity') }}</th>

                            @if ($invoiceLayout->product_price_exc_tax)
                                <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Price (Exc. Tax)') }}</th>
                            @endif

                            @if ($invoiceLayout->product_discount)
                                <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Discount') }}</th>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Vat/Tax') }}</th>
                            @endif

                            @if ($invoiceLayout->product_price_inc_tax)
                                <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>
                            @endif

                            <th class="fw-bold text-end" style="font-size:10px!important;">{{ __('Subtotal') }}</th>
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

                                @if ($invoiceLayout->product_brand)
                                    <td class="text-start" style="font-size:10px!important;">
                                        {{ $saleProduct->brand_name }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start" style="font-size:10px!important;">
                                        @if ($saleProduct->warranty_id)
                                            {{ $saleProduct->w_duration . ' ' . $saleProduct->w_duration_type }}
                                            {{ $saleProduct->w_type == 1 ? __('Warranty') : __('Guaranty') }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $saleProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <span class="fw-bold">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end" style="font-size:10px!important;">{{ $saleProduct->quantity }}/{{ $saleProduct->unit_code_name }}</td>

                                @if ($invoiceLayout->product_price_exc_tax)
                                    <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }} </td>
                                @endif

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:10px!important;">
                                        {{-- {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }} --}}
                                        @if ($saleProduct->unit_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                        @else
                                            {{ '(' . $saleProduct->unit_discount . '%)=' . App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                        @endif
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:10px!important;">
                                        ({{ $saleProduct->unit_tax_percent }}%)
                                        ={{ $saleProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_price_inc_tax)
                                    <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>
                                @endif

                                <td class="text-end" style="font-size:10px!important;">
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
                <div class="col-7">
                    @if ($invoiceLayout->show_total_in_word == 1)
                        <p style="text-transform: uppercase; font-size:9px!important;"><span class="fw-bold">{{ __('Inword') }} : </span> <span id="inword"></span> {{ __('Only') }}.</p>
                    @endif

                    @if ($invoiceLayout->account_name || $invoiceLayout->account_no || $invoiceLayout->bank_name || $invoiceLayout->bank_branch)
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($invoiceLayout->account_name)
                                <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Account Name') }} : </span> {{ $invoiceLayout->account_name }}</p>
                            @endif

                            @if ($invoiceLayout->account_no)
                                <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Account No') }} : </span> {{ $invoiceLayout->account_no }}</p>
                            @endif

                            @if ($invoiceLayout->bank_name)
                                <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Bank') }} : </span> {{ $invoiceLayout->bank_name }}</p>
                            @endif

                            @if ($invoiceLayout->bank_branch)
                                <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Branch') }} : </span> {{ $invoiceLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($sale->sale_screen == \App\Enums\SaleScreenType::ServicePosSale->value)
                        <div class="bank_details mt-2" style="width:100%; border:1px solid black;padding:2px 3px;">
                            <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Delivery Date') }} : </span> {{ isset($sale->jobCard) && isset($sale->jobCard->delivery_date_ts) ? date($dateFormat, strtotime($sale->jobCard->delivery_date_ts)) : '' }}</p>

                            <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Service Completed On') }} : </span> {{ isset($sale->jobCard) && isset($sale->jobCard->completed_at_ts) ? date($dateFormat, strtotime($sale->jobCard->completed_at_ts)) : '' }}</p>

                            <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Status') }} : </span> {{ $sale?->jobCard?->status?->name }}</p>

                            <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Brand.') }} : </span> {{ $sale?->jobCard?->brand?->name }}</p>

                            <p style="font-size:10px!important;"><span class="fw-bold">
                                    {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }} : </span> {{ $sale?->jobCard?->device?->name }}
                            </p>

                            <p style="font-size:10px!important;">
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }} : </span> {{ $sale?->jobCard?->deviceModel?->name }}
                            </p>

                            <p style="font-size:10px!important;">
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__serial_number_label']) ? $generalSettings['service_settings__serial_number_label'] : __('Serial No.') }} : </span> {{ $sale?->jobCard?->serial_no }}
                            </p>

                            <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Servicing Checklist') }} : </span>
                                @if (isset($sale->jobCard) && isset($sale->jobCard->service_checklist) && is_array($sale->jobCard->service_checklist))
                                    @foreach ($sale->jobCard->service_checklist as $key => $value)
                                        <span>
                                            @if ($value == 'yes')
                                                ✔
                                            @elseif ($value == 'no')
                                                ❌
                                            @else
                                                🚫
                                            @endif
                                            {{ $key }}
                                        </span>
                                    @endforeach
                                @endif
                            </p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Problems Reported By Customer') }} : </span> {{ $sale?->jobCard?->problems_report }}</p>
                        </div>
                    @endif

                    <div class="bank_details mt-2">
                        <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Note') }} : </span> {{ $sale?->note }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:10px!important;"><span class="fw-bold">{{ __('Ship. Address') }} : </span> {{ $sale?->shipment_address }}</p>
                    </div>
                </div>

                <div class="col-5">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Net Total Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Sale Discount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">
                                    @if ($sale->order_discount_type == 1)
                                        ({{ __('Fixed') }})={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @else
                                        ({{ $sale->order_discount }}%)
                                        ={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Sale Vat/Tax') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">
                                    ({{ $sale->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Shipment Charge') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Total Invoice Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Received Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>

                                <td class="text-end" style="font-size:10px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Due (On Invoice)') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:10px!important;">
                                    @if ($sale->due < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    @endif
                                </td>
                            </tr>

                            @if ($invoiceLayout->customer_current_balance)
                                <tr>
                                    <td class="text-end fw-bold" style="font-size:10px!important;">{{ __('Current Balance') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                    <td class="text-end" style="font-size:10px!important;">
                                        @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p class="text-uppercase borderTop fw-bold" style="font-size: 11px!important;">{{ __("Customer's Signature") }}</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-center">
                        <p class="text-uppercase borderTop fw-bold" style="font-size: 11px!important;">{{ __('Prepared By') }}</p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-end">
                        <p class="text-uppercase borderTop fw-bold" style="font-size: 11px!important;">{{ __('Authorized By') }}</p>
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
@elseif ($printPageSize == \App\Enums\PrintPageSize::AFivePage->value)
    <style>
        @media print {
            table {
                page-break-after: auto;
                margin: 0px;
                padding: 0px;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto;
                margin: 0px;
                padding: 0px;
                line-height: 1 !important;
                padding: 0px !important;
                margin: 0px !important;
            }

            th {
                page-break-inside: avoid;
                page-break-after: auto;
                margin: 0px;
                padding: 0px;
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
                    <div class="col-5">
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

                    <div class="col-7 text-end">
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
                        <h6 style="text-transform: uppercase;">{{ $invoiceLayout->invoice_heading }}</h6>
                        @php
                            $paymentStatus = '';
                            $receivable = $sale->total_invoice_amount - $sale->sale_return_amount;

                            if ($sale->due <= 0) {
                                $paymentStatus = __('Paid');
                            } elseif ($sale->due > 0 && $sale->due < $receivable) {
                                $paymentStatus = __('Partial');
                            } elseif ($receivable == $sale->due) {
                                $paymentStatus = __('Due');
                            }
                        @endphp
                        <p class="fw-bold" style="text-transform: uppercase;">{{ $paymentStatus }}</p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 1)
                @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                    <br />
                @endfor
            @endif

            <div class="row mt-1">
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

                    <img style="width: 170px; height:25px; margin-top:2px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li style="font-size:9px!important; line-height:1.5;"><span class="fw-bold">{{ __('Date') }}</span> : {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}</li>

                        @if ($sale?->jobCard)
                            <li style="font-size:9px!important; line-height:1.5;">
                                <span class="fw-bold">{{ __('Job No.') }} : </span> {{ $sale?->jobCard?->job_no }}
                            </li>
                        @endif

                        <li style="font-size:9px!important; line-height:1.5;">
                            <span class="fw-bold">{{ __('Invoice ID') }} : </span> {{ $sale->invoice_id }}
                        </li>

                        @if (isset($sale->salesOrder))
                            <li style="font-size:9px!important; line-height:1.5;">
                                <span class="fw-bold">{{ __('Order ID') }} : </span> {{ $sale?->salesOrder->order_id }}
                            </li>

                            <li style="font-size:9px!important; line-height:1.5;">
                                <span class="fw-bold">{{ __('Reference') }} : </span> {{ $sale?->reference }}
                            </li>
                        @endif

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

                            @if ($invoiceLayout->product_brand)
                                <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Brand.') }}</th>
                            @endif

                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <th class="fw-bold text-start" style="font-size:9px!important;">{{ __('Warranty') }}</th>
                            @endif

                            <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Quantity') }}</th>

                            @if ($invoiceLayout->product_price_exc_tax)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Price (Exc. Tax)') }}</th>
                            @endif

                            @if ($invoiceLayout->product_discount)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Discount') }}</th>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Vat/Tax') }}</th>
                            @endif

                            @if ($invoiceLayout->product_price_inc_tax)
                                <th class="fw-bold text-end" style="font-size:9px!important;">{{ __('Price (Inc. Tax)') }}</th>
                            @endif

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

                                    @php
                                        $productCode = $saleProduct->variant_code ? $saleProduct->variant_code : $saleProduct->product_code;
                                    @endphp

                                    {!! $invoiceLayout->product_code == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . __('P/c') . ': ' . $productCode . '</span>' : '' !!}

                                    {!! isset($saleProduct->description) ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . $saleProduct->description . '</span>' : '' !!}

                                    {!! $invoiceLayout->product_details == 1 ? '<span class="text-muted d-block" style="font-size:8px!important;line-height:1.5!important;">' . Str::limit($saleProduct->product_details, 1000, '...') . '</span>' : '' !!}
                                </td>

                                @if ($invoiceLayout->product_brand)
                                    <td class="text-start" style="font-size:9px!important;">
                                        {{ $saleProduct->brand_name }}
                                    </td>
                                @endif

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

                                @if ($invoiceLayout->product_price_exc_tax)
                                    <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }} </td>
                                @endif

                                @if ($invoiceLayout->product_discount)
                                    <td class="text-end" style="font-size:9px!important;">
                                        {{-- {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }} --}}
                                        @if ($saleProduct->unit_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                        @else
                                            {{ '(' . $saleProduct->unit_discount . '%)=' . App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                        @endif
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)
                                    <td class="text-end" style="font-size:9px!important;">
                                        ({{ $saleProduct->unit_tax_percent }}%)
                                        ={{ $saleProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_price_inc_tax)
                                    <td class="text-end" style="font-size:9px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>
                                @endif

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
                                <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Account Name') }}</span> : {{ $invoiceLayout->account_name }}</p>
                            @endif

                            @if ($invoiceLayout->account_no)
                                <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Account No') }} :</span> {{ $invoiceLayout->account_no }}</p>
                            @endif

                            @if ($invoiceLayout->bank_name)
                                <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Bank') }} :</span> {{ $invoiceLayout->bank_name }}</p>
                            @endif

                            @if ($invoiceLayout->bank_branch)
                                <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Branch') }} :</span> {{ $invoiceLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($sale->sale_screen == \App\Enums\SaleScreenType::ServicePosSale->value)
                        <div class="bank_details mt-2" style="width:100%; border:1px solid black;padding:2px 3px;">
                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Delivery Date') }} :</span> {{ isset($sale->jobCard) && isset($sale->jobCard->delivery_date_ts) ? date($dateFormat, strtotime($sale->jobCard->delivery_date_ts)) : '' }}</p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Service Completed On') }} :</span> {{ isset($sale->jobCard) && isset($sale->jobCard->completed_at_ts) ? date($dateFormat, strtotime($sale->jobCard->completed_at_ts)) : '' }}</p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Status') }} :</span> {{ $sale?->jobCard?->status?->name }}</p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Brand.') }} :</span> {{ $sale?->jobCard?->brand?->name }}</p>

                            <p style="font-size:9px!important;">
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }} :</span> {{ $sale?->jobCard?->device?->name }}
                            </p>

                            <p style="font-size:9px!important;">
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }} :</span> {{ $sale?->jobCard?->deviceModel?->name }}
                            </p>

                            <p style="font-size:9px!important;">
                                <span class="fw-bold">{{ isset($generalSettings['service_settings__serial_number_label']) ? $generalSettings['service_settings__serial_number_label'] : __('Serial No.') }} : </span> {{ $sale?->jobCard?->serial_no }}
                            </p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Servicing Checklist') }} : </span>
                                @if (isset($sale->jobCard) && isset($sale->jobCard->service_checklist) && is_array($sale->jobCard->service_checklist))
                                    @foreach ($sale->jobCard->service_checklist as $key => $value)
                                        <span>
                                            @if ($value == 'yes')
                                                ✔
                                            @elseif ($value == 'no')
                                                ❌
                                            @else
                                                🚫
                                            @endif
                                            {{ $key }}
                                        </span>
                                    @endforeach
                                @endif
                            </p>

                            <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Problems Reported By Customer') }} : </span> {{ $sale?->jobCard?->problems_report }}</p>
                        </div>
                    @endif

                    <div class="bank_details mt-2">
                        <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Note') }} : </span> {{ $sale?->note }}</p>
                    </div>

                    <div class="bank_details mt-1">
                        <p style="font-size:9px!important;"><span class="fw-bold">{{ __('Ship. Address') }} : </span> {{ $sale?->shipment_address }}</p>
                    </div>
                </div>

                <div class="col-6">
                    <table class="table print-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Net Total Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Sale Discount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
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
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Sale Vat/Tax') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    ({{ $sale->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Shipment Charge') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Total Invoice Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Received Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Due (On Invoice)') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                    @if ($sale->due < 0)
                                        ({{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }})
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    @endif
                                </td>
                            </tr>

                            @if ($invoiceLayout->customer_current_balance)
                                <tr>
                                    <td class="text-end fw-bold" style="font-size:9px!important; height:10px; line-height:10px;">{{ __('Current Balance') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</td>
                                    <td class="text-end" style="font-size:9px!important; height:10px; line-height:10px;">
                                        @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <br><br>

            <div class="row">
                <div class="col-4">
                    <div class="details_area text-start">
                        <p style="font-size:9px!important;" class="text-uppercase borderTop"><strong>{{ __("Customer's Signature") }}</strong></p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-center">
                        <p style="font-size:9px!important;" class="text-uppercase borderTop"><strong>{{ __('Prepared By') }}</strong></p>
                    </div>
                </div>

                <div class="col-4">
                    <div class="details_area text-end">
                        <p style="font-size:9px!important;" class="text-uppercase borderTop"><strong>{{ __('Authorized By') }}</strong></p>
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
                        <small style="font-size: 8px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('speeddigit.show_app_info_in_print') == true)
                            <small style="font-size: 8px!important;" class="d-block">{{ config('speeddigit.app_name_label_name') }} <span class="fw-bold">{{ config('speeddigit.name') }}</span> | {{ __('M:') }} {{ config('speeddigit.phone') }}</small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 8px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>
        @page {
            margin: 6px;
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
                                    @if ($sale?->branch)
                                        @if ($sale?->branch?->parent_branch_id)

                                            @if ($sale?->branch?->parentBranch?->logo && $invoiceLayout->show_shop_logo == 1)
                                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->parentBranch?->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:14px;color:gray;">{{ $sale->branch?->parentBranch?->name }}</span>
                                            @endif
                                        @else
                                            @if ($sale?->branch?->logo && $invoiceLayout->show_shop_logo == 1)
                                                <img style="height: 40px; width:100px;" src="{{ file_link('branchLogo', $sale->branch?->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:14px;color:gray;">{{ $sale->branch?->name }}</span>
                                            @endif
                                        @endif
                                    @else
                                        @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                                            <img style="height: 40px; width:100px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:14px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            @if ($sale?->branch)
                                {{-- <tr>
                                    <th class="text-center" style="font-size:11px;">
                                        <h6>
                                            @if ($sale?->branch?->parent_branch_id)
                                                {{ $sale?->branch?->parentBranch?->name }}
                                            @else
                                                {{ $sale?->branch?->name }}
                                            @endif
                                        </h6>
                                    </th>
                                </tr> --}}

                                <tr>
                                    <th class="text-center" style="font-size:10px; padding-top:5px;">
                                        {{ $sale->branch->address . ', ' }}
                                        {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                        {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                                        {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                                        {{ $sale->branch->country }}
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center" style="font-size:10px;">
                                            <span><b>{{ __('Email') }} : </b> {{ $sale->branch->email }}</span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center" style="font-size:10px;">
                                            <span><b>{{ __('Phone') }} : </b> {{ $sale->branch->phone }}</span>
                                        </th>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <th class="text-center" style="font-size:10px; margin-top:5px;">
                                        <span>{{ $generalSettings['business_or_shop__address'] }} </span>
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center" style="font-size:10px;">
                                            <span><b>{{ __('Phone') }} : </b> {{ $generalSettings['business_or_shop__phone'] }} </span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center" style="font-size:10px;">
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
                                <th class="text-center" style="font-size:10px;">
                                    <span>{{ __('Date') }} : {{ date($dateFormat . ' ' . $timeFormat, strtotime($sale->sale_date_ts)) }}</span>
                                </th>
                            </tr>

                            @if ($sale?->jobCard)
                                <tr>
                                    <th class="text-center" style="font-size:10px;">
                                        <span>{{ __('Job No.') }} : {{ $sale?->jobCard?->job_no }}</span>
                                    </th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-center" style="font-size:10px;">
                                    <span>{{ __('Inv No.') }} : {{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center" style="font-size:10px;">
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
                                <th style="font-size:8px;" class="text-start">{{ __('Description') }}</th>
                                <th class="text-center" style="font-size:8px;">{{ __('Qty') }}</th>
                                <th class="text-center" style="font-size:8px;">{{ __('Price') }}</th>
                                <th class="text-end" style="font-size:8px;">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant_id ? ' ' . $saleProduct->variant_name : '';
                                        $productCode = $saleProduct->variant_code ? $saleProduct->variant_code : $saleProduct->product_code;
                                    @endphp
                                    <th style="font-size:8px;line-height:2!important;padding:0px!important;" class="text-start">{{ $loop->index + 1 }}. {{ $invoiceLayout->product_code ? $productCode : '' }}</th>
                                    <th class="text-center" style="font-size:8px;line-height:2!important;padding:0px!important;">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center" style="font-size:8px;line-height:2!important;padding:0px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end" style="font-size:8px;line-height:2!important;padding:0px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                                <tr style="padding: 0px!important;margin:0px!important;padding:0px!important;">
                                    <th colspan="4" style="font-size:8px;line-height:1.5!important;border-bottom: 1px solid #000;padding:0px!important;margin:0px!important;" class="text-start"> {{ $saleProduct->p_name . $variant }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Net Total') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Sale Discount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Sale Vat/Tax') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Total Invoice Amt.') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Received Amount') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($__receivedAmount > 0 ? $__receivedAmount : $sale->paid) }}
                                    </span>
                                </th>
                            </tr>

                            @if ($changeAmount > 0 && $__receivedAmount > 0)
                                <tr>
                                    <th class="text-end" style="font-size:9px;">{{ __('Change') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <th class="text-end" style="font-size:9px;">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($changeAmount) }}
                                        </span>
                                    </th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Due (On Invoice)') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        @if ($sale->due < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                        @endif
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end" style="font-size:9px;">{{ __('Current Balance') }} : {{ $sale?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th class="text-end" style="font-size:9px;">
                                    <span>
                                        @if ($amounts['closing_balance_in_flat_amount'] < 0)
                                            ({{ App\Utils\Converter::format_in_bdt(abs($amounts['closing_balance_in_flat_amount'])) }})
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($amounts['closing_balance_in_flat_amount']) }}
                                        @endif
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

                            @if (config('speeddigit.show_app_info_in_print') == true)
                                <tr>
                                    <th class="text-center">
                                        <span>{{ config('speeddigit.app_name_label_name') }} <b>{{ config('speeddigit.name') }}</b> | {{ __('M:') }} {{ config('speeddigit.phone') }}</span>
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
