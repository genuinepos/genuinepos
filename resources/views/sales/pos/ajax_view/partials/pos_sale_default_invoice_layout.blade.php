@php
    $defaultLayout = DB::table('invoice_layouts')
        ->where('is_default', 1)
        ->first();
@endphp
@if ($defaultLayout->layout_design == 1)
    <div class="sale_print_template d-hide">
        <style>
            @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 4%;margin-right: 4%;}
        </style>
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div id="header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $defaultLayout->header_text }}</p>
                                <p>{{ $defaultLayout->sub_heading_1 }}</p>
                                <p>{{ $defaultLayout->sub_heading_2 }}</p>
                                <p>{{ $defaultLayout->sub_heading_3 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if ($sale->branch)
                                    @if ($sale->branch->logo != 'default.png')
                                        <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                    @endif
                                @else
                                    @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                        <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->invoice_heading }}</h5>
                                <h6>
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp

                                    @if ($sale->due <= 0)
                                    @lang('menu.paid')
                                    @elseif ($sale->due > 0 && $sale->due < $payable) Partial
                                        @elseif($payable==$sale->due)
                                            Due
                                    @endif
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <p class="company_name" style="text-transform: uppercase;">
                                        <strong>{{ $sale->branch->name }}</strong>
                                    </p>

                                    <p class="company_address">,
                                        {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><b>@lang('menu.phone')</b> : {{ $sale->branch->phone }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email && $sale->branch->email)
                                        <p><b>@lang('menu.email')</b> : {{ $sale->branch->email }}</p>
                                    @endif
                                @else
                                    <p class="company_name" style="text-transform: uppercase;">
                                        <strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                                    </p>

                                    <p class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><b>@lang('menu.phone')</b> : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email && json_decode($generalSettings->business, true)['email'])
                                        <p><b>@lang('menu.email')</b> : {{ json_decode($generalSettings->business, true)['email'] }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($defaultLayout->is_header_less == 1)
                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.customer'): </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li><strong>@lang('menu.address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li><strong>@lang('menu.tax_number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li><strong>@lang('menu.phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->invoice_heading }}</h5>
                                <h6>
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp

                                    @if ($sale->due <= 0)
                                    @lang('menu.paid')
                                    @elseif ($sale->due > 0 && $sale->due < $payable)
                                    @lang('menu.partial')
                                    @elseif($payable==$sale->due)
                                    @lang('menu.due')
                                    @endif
                                </h6>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong> Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}
                            </li>
                            <li><strong> @lang('menu.user') : </strong> {{ $sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-startx">@lang('menu.sl')</th>
                            <th class="text-startx">@lang('menu.description')</th>
                            <th class="text-startx">@lang('menu.sold_quantity')</th>
                            @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                <th scope="col">@lang('menu.warranty')</th>
                            @endif

                            <th class="text-startx">Price</th>

                            @if ($defaultLayout->product_discount)
                                <th class="text-startx">@lang('menu.discount')</th>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <th class="text-startx">@lang('menu.tax')</th>
                            @endif

                            <th class="text-startx">@lang('menu.sub_total')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1}} </td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }}({{ $sale_product->unit }}) </td>

                                @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warrantiy' : 'Guaranty' }}
                                            {!! $defaultLayout->product_w_discription ? '<br><small class="text-muted">' . ($sale_product->description == 'null' ? '' : $sale_product->description) . '</small>' : '' !!}
                                        @else
                                            <b>No</b>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">{{ $sale_product->unit_price_inc_tax }} </td>
                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)
                                    <td class="text-start">{{ $sale_product->unit_tax_percent }}%</td>
                                @endif

                                <td class="text-start">{{ $sale_product->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($sale->sale_products) > 6)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>Continued To this next page....</em></h6>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($defaultLayout->show_total_in_word)
                        <p style="text-transform: uppercase;">
                            <b>@lang('menu.in_word'): </b> <span id="inword"> ONLY.</span>
                        </p>
                    @endif

                    @if (
                        $defaultLayout->account_name ||
                        $defaultLayout->account_no ||
                        $defaultLayout->bank_name ||
                        $defaultLayout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($defaultLayout->account_name)
                                <p>@lang('menu.account_name') : {{ $defaultLayout->account_name  }}</p>
                            @endif

                            @if ($defaultLayout->account_no)
                                <p>@lang('menu.account_no') : {{ $defaultLayout->account_no }}</p>
                            @endif

                            @if ($defaultLayout->bank_name)
                                <p>@lang('menu.bank') : {{ $defaultLayout->bank_name }}</p>
                            @endif

                            @if ($defaultLayout->bank_branch)
                                <p>@lang('menu.branch') : {{ $defaultLayout->bank_branch }}</p>
                            @endif
                        </div>
                      @endif
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-endx"><strong>@lang('menu.net_total_amount') :</strong></td>
                                <td class="net_total text-end">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong>@lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    @if ($sale->order_discount_type == 1)
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong> @lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                    ({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong>@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong> @lang('menu.total_payable') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong> @lang('menu.total_paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-endx"><strong>@lang('menu.total_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('menu.customers_signature')</b></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('menu.checked_by')</b></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('menu.approved_by')</b></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><b>@lang('menu.signature_of_authority')</b></p>
                    </div>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $defaultLayout->invoice_notice ? '<strong>Attention : </strong>' . $defaultLayout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $defaultLayout->footer_text }}</p>
                    </div>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-center">
                        <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    @if (config('company.print_on_sale'))
                        <div class="col-4 text-center">
                            <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                            <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                        </div>
                    @endif

                    <div class="col-4 text-center">
                        <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Packing slip print templete-->
    <div class="sale_print_template d-hide">
        <style>@page{margin: 8px;}</style>
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($defaultLayout->show_shop_logo == 1)
                                        @if ($sale->branch)
                                            @if ($sale->branch->logo != 'default.png')
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600; text-transform: uppercase;">
                                                    {{ $sale->branch->name }}
                                                </span>
                                            @endif
                                        @else
                                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                                <img style="height: 40px; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                            @else
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600; text-transform: uppercase;">
                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                </span>
                                            @endif
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            @if ($sale->branch)
                                <tr>
                                    <th class="text-center">
                                        <h6>{{$sale->branch->name . '/' . $sale->branch->branch_code }}</h6>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span>{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('menu.phone') :</b>  {{ $sale->branch->phone }}</span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('menu.email') :</b> {{ $sale->branch->email }}</span>
                                    </th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center">
                                        <span>{{ json_decode($generalSettings->business, true)['address'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('menu.phone') :</b> {{ json_decode($generalSettings->business, true)['phone'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('menu.email') :</b> {{ json_decode($generalSettings->business, true)['email'] }} </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-3">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.date'):</b> <span>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.inv_no') </b> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.customer'):</b> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-startx">@lang('menu.description')</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Price</th>
                                <th class="text-endx">@lang('menu.total')</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($sale->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' '.$saleProduct->variant->variant_name : '';
                                    @endphp
                                    <th class="text-startx">{{ $loop->index + 1 }}. {{ $saleProduct->product->name.$variant }} </th>

                                    <th class="text-center">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</th>
                                    <th class="text-endx">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100 float-end">
                        <thead>
                            <tr>
                                <th class="text-endx">@lang('menu.net_total') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-endx">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-endx">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-endx">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.total_payable') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-endx">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx"><strong> @lang('menu.total_paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></th>
                                <th class="text-endx">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx"><strong> Change Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></th>
                                <th class="text-endx">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->change_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-endx"><strong>@lang('menu.total_due') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></th>
                                <th class="text-endx">
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
                                    <span>{{ $defaultLayout->invoice_notice ?  $defaultLayout->invoice_notice : '' }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $defaultLayout->footer_text ?  $defaultLayout->footer_text : '' }}</span>
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

                            @if (config('company.print_on_sale'))
                                <tr>
                                    <th class="text-center">
                                        <span>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b> </span>
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
