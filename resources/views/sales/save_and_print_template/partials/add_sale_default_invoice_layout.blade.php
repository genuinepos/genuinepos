@php
    $invoiceLayout = DB::table('invoice_layouts')->where('branch_id', null)->where('is_default', 1)->first();
    $invoiceLayout = $sale?->branch?->branchSetting?->addSaleInvoiceLayout ? $sale?->branch?->branchSetting?->addSaleInvoiceLayout : $invoiceLayout;
@endphp
@if ($invoiceLayout->layout_design == 1)
    <div class="sale_print_template">
        <style>
            @page {size:a4;margin-top: 0.8cm;/* margin-bottom: 35px;  */margin-left: 10px;margin-right: 10px;}
            div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        </style>
        <div class="details_area">
            @if ($invoiceLayout->is_header_less == 0)
                <div class="row">
                    <div class="col-md-12 text-center">
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
                                @if ($sale?->branch)
                                    @if ($sale?->branch?->parent_branch_id)

                                        {{ $sale?->branch?->parentBranch?->name }}
                                    @else

                                        {{ $sale?->branch?->name }}
                                    @endif
                                @else

                                    {{ $generalSettings['business__shop_name'] }}
                                @endif
                            </strong>
                        </p>

                        <p>
                            @if ($sale?->branch)

                                {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state. ', ' : '' }}
                                {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code. ', ' : '' }}
                                {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                            @else

                                {{ $generalSettings['business__address'] }}
                            @endif
                        </p>

                        <p>
                            @php
                                $email = $sale?->branch?->email ? $sale?->branch?->email : $generalSettings['business__email'];
                                $phone = $sale?->branch?->phone ? $sale?->branch?->phone : $generalSettings['business__phone'];
                            @endphp

                            @if ($invoiceLayout->branch_email)
                                <strong>{{ __("Email") }} : </strong> <b>{{ $email }}</b>,
                            @endif

                            @if ($invoiceLayout->branch_phone)
                                <strong>{{ __("Phone") }} : </strong> <b>{{ $phone }}</b>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 0)
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <h5 style="text-transform: uppercase;"><strong>{{ $invoiceLayout->invoice_heading }}</strong></h5>
                    </div>
                </div>
            @endif

            @if ($invoiceLayout->is_header_less == 1)
                @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        @if ($invoiceLayout->customer_name)
                            <li style="font-size:11px!important;"><strong>{{ __("Customer") }} : </strong>
                                {{ $sale?->customer?->name }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_address)
                            <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong>
                                {{ $sale?->customer?->address }}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_tax_no)
                            <li style="font-size:11px!important;"><strong>{{ __("Tax Number") }} : </strong>
                                {{ $sale?->customer?->tax_number}}
                            </li>
                        @endif

                        @if ($invoiceLayout->customer_phone)
                            <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong> {{ $sale?->customer?->phone }}</li>
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
                            <strong>{{ __("Invoice ID") }} : </strong> {{ $sale->invoice_id }}
                        </li>

                        <li style="font-size:11px!important;">
                            <strong>{{ __("Date") }} : </strong> {{ date($generalSettings['business__date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}
                        </li>

                        <li style="font-size:11px!important;">
                            <strong>{{ __("Created By") }} : </strong> {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("S/L") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Description") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Exc. Tax)') }}</th>
                            @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Warranty") }}</th>
                            @endif

                            @if ($invoiceLayout->product_discount)
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Discount") }}</th>
                            @endif

                            @if ($invoiceLayout->product_tax)
                                <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                            @endif

                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Price (Inc. Tax)') }}</th>

                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $saleProduct->p_name }}

                                    @if ($saleProduct->product_variant_id)

                                        -{{ $saleProduct->variant_name }}
                                    @endif
                                    {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                                </td>

                                <td class="text-start">{{ $saleProduct->quantity }}/{{ $saleProduct->unit_code_name }}</td>

                                @if ($invoiceLayout->product_w_type || $invoiceLayout->product_w_duration || $invoiceLayout->product_w_discription)
                                    <td class="text-start">
                                        @if ($saleProduct->warranty_id)
                                            {{ $saleProduct->w_duration . ' ' . $saleProduct->w_duration_type }}
                                            {{ $saleProduct->w_type == 1 ? __("Warranty") : __("Guaranty") }}
                                            {!! $invoiceLayout->product_w_discription ? '<br><small class="text-muted">' . $saleProduct->w_description . '</small>' : '' !!}
                                        @else
                                            <strong>{{ __("No") }}</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }} </td>

                                @if ($invoiceLayout->product_discount)

                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($invoiceLayout->product_tax)

                                    <td class="text-end">
                                        ({{ $saleProduct->unit_tax_percent }}%)={{ $saleProduct->unit_tax_amount }}
                                    </td>
                                @endif

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($sale->sale_products) > 15)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>@lang('menu.continued_to_this_next_page')....</em></h6>
                    </div>
                </div>

                @if ($invoiceLayout->is_header_less == 1)
                    @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($invoiceLayout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><strong>@lang('menu.in_word')</strong> <span id="inword"></span> {{ __("Only") }}.</p>
                    @endif

                    @if (
                        $invoiceLayout->account_name ||
                        $invoiceLayout->account_no ||
                        $invoiceLayout->bank_name ||
                        $invoiceLayout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($invoiceLayout->account_name)
                                <p>{{ __("Account Name") }} : {{ $invoiceLayout->account_name }}</p>
                            @endif

                            @if ($invoiceLayout->account_no)
                                <p>{{ __("Account No") }} : {{ $invoiceLayout->account_no }}</p>
                            @endif

                            @if ($invoiceLayout->bank_name)
                                <p>{{ __("Bank") }} : {{ $invoiceLayout->bank_name }}</p>
                            @endif

                            @if ($invoiceLayout->bank_branch)
                                <p>{{ __("Branch") }} : {{ $invoiceLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>{{ __("Net Total Amount") }} :{{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="net_total text-end">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><strong> {{ __("Sale Discount") }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="order_discount text-end">
                                    @if ($sale->order_discount_type == 1)
                                        ({{ __("Fixed") }})={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @else
                                        ({{ $sale->order_discount }}%)={{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __("Sale Tax") }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="order_tax text-end">
                                    ({{ $sale->order_tax_percent }} %)={{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }} </strong></td>
                                <td class="shipment_charge text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __("Received Amount") }} : {{ $generalSettings['business__currency'] }} </strong></td>
                                <td class="total_payable text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_receivable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __("Due (On Invoice)") }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($receivedAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong>{{ __("Current Balance") }} : {{ $generalSettings['business__currency'] }}</strong></td>
                                <td class="total_due text-end">
                                    {{ App\Utils\Converter::format_in_bdt(0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>{{ __("Customer\'s Signature") }}</strong></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>{{ __("Checked By") }}</strong></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>{{ __("Approved By") }}</strong></p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>{{ __("Authorized By") }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $invoiceLayout->invoice_notice ? '<strong>'. __("Attention") . '</strong>' . $invoiceLayout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $invoiceLayout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small>{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (env('PRINT_SD_SALE') == true)
                            <small class="d-block">{{ __("Powered By") }} <strong>@lang('SpeedDigit Software Solution').</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small>{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>@page{margin: 8px;}</style>
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
                                        @if ($generalSettings['business__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)

                                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                                        @else

                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                                        @endif
                                    @endif
                                </th>
                            </tr>

                             @if ($sale?->branch)
                                <tr>
                                    <th class="text-center">
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
                                    <th class="text-center">
                                        {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                                        {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state. ', ' : '' }}
                                        {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code. ', ' : '' }}
                                        {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center">
                                            <span><b>{{ __("Email") }} : </b> {{ $sale->branch->email }}</span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center">
                                            <span><b>{{ __("Phone") }} : </b>  {{ $sale->branch->phone }}</span>
                                        </th>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <th class="text-center">
                                        <span>{{ $generalSettings['business__address'] }} </span>
                                    </th>
                                </tr>

                                @if ($invoiceLayout->branch_email)
                                    <tr>
                                        <th class="text-center">
                                            <span><b>{{ __("Phone") }} : </b> {{ $generalSettings['business__phone'] }} </span>
                                        </th>
                                    </tr>
                                @endif

                                @if ($invoiceLayout->branch_phone)
                                    <tr>
                                        <th class="text-center">
                                            <span><b>{{ __("Email") }} : </b> {{ $generalSettings['business__email'] }} </span>
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
                                <th class="text-center">
                                    <strong>{{ __("Date") }} : </strong> <span>{{ date($generalSettings['business__date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>{{ __("Inv No.") }} : </strong> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <strong>{{ __("Customer Name") }} : </strong> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th style="width: 50%;" class="text-start">{{ __("Description") }}</th>
                                <th class="text-center">{{ __("Qty") }}</th>
                                <th class="text-center">{{ __("Price") }}</th>
                                <th class="text-end">{{ __("Total") }}</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->product_variant_id ? ' '.$saleProduct->variant_name : '';
                                    @endphp
                                    <th style="width: 50%;" class="text-start">{{ $loop->index + 1 }}. {{ Str::limit($saleProduct->p_name, 25, '').$variant }}</th>
                                    <th class="text-center">{{ (float)$saleProduct->quantity }}</th>
                                    <th class="text-center">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __("Net Total") }} : {{ $generalSettings['business__currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __("Sale Discount") }} : {{ $generalSettings['business__currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __("Sale Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __("Received Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($receivedAmount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">{{ __("Change") }} : {{ $generalSettings['business__currency'] }}</th>
                                <th class="total_paid text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($changeAmount > 0 ? $changeAmount : 0) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> {{ __("Due") }} : {{ $generalSettings['business__currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
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
                                        <span>{{ __("Powered By") }} <b>{{ __("SpeedDigit Software Solution") }}.</b> </span>
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
