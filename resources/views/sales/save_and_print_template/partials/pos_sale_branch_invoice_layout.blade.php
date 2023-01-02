@if ($sale->branch->pos_sale_invoice_layout->layout_design == 1)
    <div class="sale_print_template">
        <style>
            @page {size:a4;margin-top: 0.8cm;/* margin-bottom: 35px;  */margin-left: 4%;margin-right: 4%;}
            div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        </style>
        <div class="details_area">
            @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $sale->branch->pos_sale_invoice_layout->header_text }}</p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_1 }}<p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_2 }}<p>
                                <p>{{ $sale->branch->pos_sale_invoice_layout->sub_heading_3 }}<p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            @if ($sale->branch->pos_sale_invoice_layout->show_shop_logo == 1)
                                @if ($sale->branch->logo != 'default.png')
                                    <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                @else
                                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                @endif
                            @endif
                        </div>

                        <div class="col-8">
                            <div class="heading text-end">
                                <p class="company_name" style="text-transform: uppercase;"><strong>{{ $sale->branch->name }}</strong></p>

                                <p class="company_address">
                                    {{ $sale->branch->pos_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                    {{ $sale->branch->pos_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                    {{ $sale->branch->pos_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                    {{ $sale->branch->pos_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                </p>

                                @if ($sale->branch->pos_sale_invoice_layout->branch_phone)
                                    <p><strong>@lang('menu.phone') :</strong>{{ $sale->branch->phone }}</p>
                                @endif

                                @if ($sale->branch->pos_sale_invoice_layout->branch_email && $sale->branch->email)
                                    <p><strong>@lang('menu.email') :</strong>{{ $sale->branch->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->pos_sale_invoice_layout->invoice_heading }}</h5>
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
                        </div>
                    </div>
                </div>
            @endif

            @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $sale->branch->pos_sale_invoice_layout->gap_from_top; $i++)
                    <br/>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.customer') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($sale->branch->pos_sale_invoice_layout->customer_address)
                                <li><strong>@lang('menu.address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->customer_tax_no)
                                <li><strong>@lang('menu.tax_number') : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->customer_phone)
                                <li><strong>@lang('menu.phone') : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif

                            @if ($generalSettings['reward_point_settings']['enable_cus_point'] == 1)
                                <li>
                                    <strong>{{ $generalSettings['reward_point_settings']['point_display_name'] }} : </strong> {{ $sale->customer ? $sale->customer->point : 0 }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4 text-center">
                        @if ($sale->branch->pos_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->pos_sale_invoice_layout->invoice_heading }}</h5>
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
                        <img style="width: 170px; height:40px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('menu.invoice_no') : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong>@lang('menu.date'):  </strong> {{ date($generalSettings['business']['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat ,strtotime($sale->time )) }}</li>
                            <li><strong> @lang('menu.entered_by') : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table  modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('menu.sl')</th>
                            <th class="text-start">@lang('menu.department')</th>
                            <th class="text-start">{{ __('Sold Price') }}</th>
                            @if ($sale->branch->pos_sale_invoice_layout->product_w_type || $sale->branch->pos_sale_invoice_layout->product_w_duration || $sale->branch->pos_sale_invoice_layout->product_w_discription)
                                <th class="text-start">@lang('menu.warranty')</th>
                            @endif
                            <th class="text-start">@lang('menu.price')</th>
                            @if ($sale->branch->pos_sale_invoice_layout->product_discount)
                                <th class="text-start">@lang('menu.discount')</th>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->product_tax)
                                <th class="text-start">@lang('menu.tax')</th>
                            @endif

                            <th class="text-start">@lang('menu.sub_total')</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}{!! $sale_product->ex_quantity != 0 ? '(<strong>EX:</strong>'.$sale_product->ex_quantity.')' : '' !!}
                                    @endif
                                    {!! $sale->branch->pos_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                @if ($sale->branch->pos_sale_invoice_layout->product_w_type || $sale->branch->pos_sale_invoice_layout->product_w_duration || $sale->branch->pos_sale_invoice_layout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $sale->branch->pos_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @else
                                            <strong>No</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_price_inc_tax) }}</td>

                                @if ($sale->branch->pos_sale_invoice_layout->product_discount)
                                    <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_discount_amount) }}</td>
                                @endif

                                @if ($sale->branch->pos_sale_invoice_layout->product_tax)
                                    <td class="text-start">{{ $sale_product->unit_tax_percent }}</td>
                                @endif

                                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale_product->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (count($sale->sale_products) > 15)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>@lang('menu.dontinued_to_this_next_page')....</em></h6>
                    </div>
                </div>
                @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                    @for ($i = 0; $i < $sale->branch->add_sale_invoice_layout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif

            <div class="row">
                <div class="col-md-6">
                    @if ($sale->branch->pos_sale_invoice_layout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><b>@lang('menu.in_word'): </b> <span id="inword"></span> @lang('menu.only').</p>
                    @endif

                    @if (
                        $sale->branch->pos_sale_invoice_layout->account_name ||
                        $sale->branch->pos_sale_invoice_layout->account_no ||
                        $sale->branch->pos_sale_invoice_layout->bank_name ||
                        $sale->branch->pos_sale_invoice_layout->bank_branch
                    )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($sale->branch->pos_sale_invoice_layout->account_name)
                                <p>@lang('menu.account_name') : {{ $sale->branch->pos_sale_invoice_layout->account_name }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->account_no)
                                <p>@lang('menu.account_no') : {{ $sale->branch->pos_sale_invoice_layout->account_no }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->bank_name)
                                <p>@lang('menu.bank') : {{ $sale->branch->pos_sale_invoice_layout->bank_name }}</p>
                            @endif

                            @if ($sale->branch->pos_sale_invoice_layout->bank_branch)
                                <p>@lang('menu.branch') : {{ $sale->branch->pos_sale_invoice_layout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>@lang('menu.net_total_amount') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="net_total text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('menu.order_discount') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="order_discount text-end">
                                    @if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ( {{ $sale->order_discount }}%)
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('menu.order_tax') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="order_tax text-end">
                                    ({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('menu.shipment_charge') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="shipment_charge text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>

                            @if ($previous_due != 0)
                                <tr>
                                    <td class="text-end"><strong> @lang('menu.previous_due') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                    <td class="total_payable text-end">
                                        {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end"><strong> @lang('menu.total_payable') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="total_payable text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> @lang('menu.total_paid') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                </td>
                            </tr>

                            @if ($change_amount > 0)
                                <tr>
                                    <td class="text-end"><strong> @lang('menu.change_amount') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                    <td class="total_paid text-end">
                                        {{ App\Utils\Converter::format_in_bdt($change_amount) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="text-end"><strong> @lang('menu.total_due') : {{ $generalSettings['business']['currency'] }}</strong></td>
                                <td class="total_due text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_due) }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('menu.customers_signature')</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('menu.checked_by')</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('menu.approved_by')</strong> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>@lang('menu.signature_of_authority')</strong></p>
                    </div>
                </div>
            </div><br/>

            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $sale->branch->pos_sale_invoice_layout->invoice_notice ? '<strong>Attention : </strong>' . $sale->branch->pos_sale_invoice_layout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $sale->branch->pos_sale_invoice_layout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small>@lang('menu.print_date') : {{ date($generalSettings['business']['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (env('PRINT_SD_SALE') == true)
                            <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd').</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>@page{margin: 8px;}</style>
    <div class="sale_print_template">
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($sale->branch->logo != 'default.png')
                                        <img style="height: 40px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ $sale->branch->name }}</span>
                                    @endif
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{$sale->branch->name . '/' . $sale->branch->branch_code }}</span>
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
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.date'):</b> <span>{{ date($generalSettings['business']['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.inv_no'): </b> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('menu.customer'):</b> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>

                            @if ($generalSettings['reward_point_settings']['enable_cus_point'] == 1)
                                <tr>
                                    <th class="text-center">
                                        <b>{{ $generalSettings['reward_point_settings']['point_display_name'] }} :</b>
                                        <span>{{ $sale->customer ? $sale->customer->point : 0 }}</span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-start">@lang('menu.description')</th>
                                <th class="text-center">@lang('menu.qty')</th>
                                <th class="text-center">@lang('menu.price')</th>
                                <th class="text-end">@lang('menu.total')</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($sale->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' '.$saleProduct->variant->variant_name : '';
                                    @endphp
                                    <th class="text-start">{{ $loop->index + 1 }}. {{ Str::limit($saleProduct->product->name, 25, '').$variant }}{!! $saleProduct->ex_quantity != 0 ? '(<strong>EX:</strong>'.$saleProduct->ex_quantity.')' : '' !!} </th>
                                    <th class="text-center">{{ (float) $saleProduct->quantity }}</th>
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
                                <th class="text-end">@lang('menu.net_total') : {{ $generalSettings['business']['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('menu.discount') : {{ $generalSettings['business']['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('menu.order_tax') : {{ $generalSettings['business']['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            @if ($previous_due != 0)
                                <tr>
                                    <th class="text-end">@lang('menu.previous_due') : {{ $generalSettings['business']['currency'] }}</th>
                                    <th class="text-end">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                        </span>
                                    </th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-end">@lang('menu.payable') : {{ $generalSettings['business']['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('menu.paid') : {{ $generalSettings['business']['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                    </span>
                                </th>
                            </tr>

                            @if ($sale->ex_status == 0)
                                @if ($change_amount > 0)
                                    <tr>
                                        <th class="text-end"><strong> @lang('menu.change_amount') : {{ $generalSettings['business']['currency'] }} </strong></th>
                                        <th class="total_paid text-end">
                                            <span>
                                                {{ App\Utils\Converter::format_in_bdt($change_amount) }}
                                            </span>
                                        </th>
                                    </tr>
                                @endif
                            @endif

                            <tr>
                                <th class="text-end"> <strong>@lang('menu.due') : {{ $generalSettings['business']['currency'] }}</strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_due) }}
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
                                    <span>{{ $sale->branch->pos_sale_invoice_layout->invoice_notice ?  $sale->branch->pos_sale_invoice_layout->invoice_notice : '' }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $sale->branch->pos_sale_invoice_layout->footer_text ?  $sale->branch->pos_sale_invoice_layout->footer_text : '' }}</span>
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
                                        <span>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b> </span>
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
