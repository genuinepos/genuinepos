@php
    $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
@endphp
@if ($defaultLayout->layout_design == 1)
    <div class="sale_print_template">
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
                                        <img style="height: 60px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else 
                                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $sale->branch->name }}</span>
                                    @endif
                                @else 
                                    @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                        <img src="{{ asset('public/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
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
                                        Paid
                                    @elseif ($sale->due > 0 && $sale->due < $payable) 
                                        Partial 
                                    @elseif($payable==$sale->due)
                                        Due
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <h6 class="company_name">
                                        {{ $sale->branch->name . '/' . $sale->branch->branch_code }}
                                    </h6>

                                    <p class="company_address">
                                        {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><b>Phone :</b>  {{ $sale->branch->phone }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <p><b>Email :</b> {{ $sale->branch->email }}</p>
                                    @endif 
                                @else
                                    <h6 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                    </h6>

                                    <p class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </p>

                                    @if ($defaultLayout->branch_phone)
                                        <p><strong>Phone :</strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <p><strong>Email :</strong> {{ json_decode($generalSettings->business, true)['email'] }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($defaultLayout->is_header_less == 1)
                @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                    </br>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer : </strong> 
                                {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li><strong>Address : </strong> 
                                    {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li><strong>Tax Number : </strong> 
                                    {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</li>
                            @endif

                            @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == 1)
                                <li><strong>{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }} : </strong>  
                                    {{ $sale->customer ? $sale->customer->point : 0 }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4 text-center">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->invoice_heading }}</h5>
                                <h6>
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp

                                    @if ($sale->due <= 0)
                                        Paid
                                    @elseif ($sale->due > 0 && $sale->due < $payable) 
                                        Partial 
                                    @elseif($payable==$sale->due)
                                        Due
                                    @endif
                                </h6>
                            </div>
                        @endif
                        <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong> Date : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</li>
                            <li><strong> Entered By : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">Descrpiton</th>
                            <th class="text-start">Sold Qty</th>
                            @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                <th class="text-start">Warranty</th>
                            @endif
                            <th class="text-start">Price</th>
                            @if ($defaultLayout->product_discount)
                                <th class="text-start">Discount</th>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <th class="text-start">Tax</th>
                            @endif
                            <th class="text-start">SubTotal</th>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}{!! $sale_product->ex_quantity != 0 ? '(<b>EX:</b>'.$sale_product->ex_quantity.')' : '' !!}
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }}({{ $sale_product->unit }}) </td>

                                @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $defaultLayout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @else 
                                            <b>No</b>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale_product->unit_price_inc_tax) }} </td>

                                @if ($defaultLayout->product_discount)
                                    <td class="text-start">
                                        {{ App\Utils\Converter::format_in_bdt($sale_product->unit_discount_amount) }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)
                                    <td class="text-start">
                                        {{ $sale_product->unit_tax_percent }}%
                                    </td>
                                @endif

                                <td class="text-start">
                                    {{ App\Utils\Converter::format_in_bdt($sale_product->subtotal) }}
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
                        <h6><em>Continued To this next page....</em></h6>
                    </div>
                </div>
                @if ($defaultLayout->is_header_less == 1)
                    @for ($i = 0; $i < $defaultLayout->gap_from_top; $i++)
                        <br/>
                    @endfor
                @endif
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    @if ($defaultLayout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><b>In Word : </b> <span id="inword"></span> ONLY.</p> 
                    @endif

                    @if (
                        $defaultLayout->account_name || 
                        $defaultLayout->account_no || 
                        $defaultLayout->bank_name || 
                        $defaultLayout->bank_branch  
                      )
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($defaultLayout->account_name)
                                <p>Account Name : {{ $defaultLayout->account_name }}</p>
                            @endif

                            @if ($defaultLayout->account_no)
                                <p>Account No : {{ $defaultLayout->account_no }}</p>
                            @endif

                            @if ($defaultLayout->bank_name)
                                <p>Bank : {{ $defaultLayout->bank_name }}</p>
                            @endif

                            @if ($defaultLayout->bank_branch)
                                <p>Branch : {{ $defaultLayout->bank_branch }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>Net Total Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="net_total text-end">{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</td>
                            </tr> 
                            <tr>
                                <td class="text-end"><strong> Order Discount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="order_discount text-end">
                                    @if ($sale->order_discount_type == 1)
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} (Fixed)
                                    @else
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ( {{ $sale->order_discount }}%)
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="order_tax text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Shipment charge : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="shipment_charge text-end">
                                    {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                </td>
                            </tr>
                    
                            @if ($previous_due != 0)
                                <tr>
                                    <td class="text-end"><strong> Previous Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="total_payable text-end">
                                        {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                    </td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td class="text-end"><strong> Total Payable : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_payable text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_paid text-end">
                                    {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                </td>
                            </tr>

                            @if ($change_amount > 0)
                                <tr>
                                    <td class="text-end"><strong> Change Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                    <td class="total_paid text-end">
                                        {{ App\Utils\Converter::format_in_bdt($change_amount > 0 ? $change_amount : 0) }}
                                    </td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td class="text-end"><strong> Total Due : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="total_due text-end">
                                    {{ App\Utils\Converter::format_in_bdt($total_due > 0 ? $total_due : 0) }}
                                </td>
                            </tr>
                        
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Customer's signature</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Checked By</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Approved By</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Signature Of Authority</strong></p>
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
            </div><br>

            <div id="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <p><strong>Our Sister Concern</strong></p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:30px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:30px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:30px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:30px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                        </div>
                    </div>
                </div>
            
                <div class="row mt-1">
                    <div class="col-4 text-center">
                        <small>Print Date : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>
                    
                    <div class="col-4 text-center">
                        @if (env('PRINT_SD_SALE') == true)
                            <small class="d-block">Software By <strong>SpeedDigit Pvt. Ltd.</strong></small>
                        @endif
                    </div>
                    
                    <div class="col-4 text-center">
                        <small>Print Time : {{ date($timeFormat) }}</small>
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
                                    @if ($defaultLayout->show_shop_logo == 1)
                                        @if ($sale->branch)
                                            @if ($sale->branch->logo != 'default.png')
                                                <img style="height: 40px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                                            @else 
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black; font-weight: 600;">{{ $sale->branch->name }}</span>
                                            @endif
                                        @else 
                                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                                <img style="height: 40px; width:200px;" src="{{ asset('public/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                                            @else 
                                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
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
                                        <span><b>Phone :</b>  {{ $sale->branch->phone }}</span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>Email :</b> {{ $sale->branch->email }}</span>
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
                                        <span><b>Phone :</b> {{ json_decode($generalSettings->business, true)['phone'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>Email :</b> {{ json_decode($generalSettings->business, true)['email'] }} </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
                
                <div class="customer_info mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <b>Date:</b> <span> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</span> 
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>INV NO: </b> <span>{{ $sale->invoice_id }}</span> 
                                </th>
                            </tr>
                            
                            <tr>
                                <th class="text-center">
                                    <b>Customer:</b> <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span> 
                                </th>
                            </tr>

                            @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == 1)
                                <tr>
                                    <th class="text-center">
                                        <b>{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }} : </b>  
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
                                <th class="text-start"> Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($sale->sale_products as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->variant ? ' '.$saleProduct->variant->variant_name : '';
                                    @endphp
                                    <th class="text-start">
                                        {{ $loop->index + 1 }}. {{ Str::limit($saleProduct->product->name, 25, '').$variant }}{!! $saleProduct->ex_quantity != 0 ? '(<strong>EX:</strong>'.$saleProduct->ex_quantity.')' : '' !!} 
                                    </th>
                                    
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
                                <th class="text-end">Net Total : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">Discount : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>
                            
                            <tr>
                                <th class="text-end">Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            @if ($previous_due > 0)
                                <tr>
                                    <th class="text-end">Previous Due : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <th class="text-end">
                                        <span>
                                            {{ App\Utils\Converter::format_in_bdt($previous_due) }}
                                        </span>
                                    </th>
                                </tr>
                            @endif
                            
                            <tr>
                                <th class="text-end">Total Payable : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($paying_amount) }}
                                    </span>
                                </th>
                            </tr>

                            @if ($sale->ex_status == 0)
                                @if ($change_amount > 0)
                                    <tr>
                                        <th class="text-end"><strong> Change Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></th>
                                        <th class="total_paid text-end">
                                            <span>
                                                {{ App\Utils\Converter::format_in_bdt($change_amount) }}
                                            </span>
                                        </th>
                                    </tr>
                                @endif
                            @endif

                            <tr>
                                <th class="text-end"> Due : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
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

                            @if (env('PRINT_SD_SALE') == true)
                                <tr>
                                    <th class="text-center">
                                        <span>Software By <b>SpeedDigit Pvt. Ltd.</b> </span>
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