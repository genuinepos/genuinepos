@if ($sale->branch->add_sale_invoice_layout->layout_design == 1)
    <div class="sale_print_template">
        <div class="details_area">
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <p>{{ $sale->branch->add_sale_invoice_layout->header_text }}</p>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}<p/>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}<p/>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}<p/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->add_sale_invoice_layout->invoice_heading }}</h5>
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
                                <h5 class="company_name">
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                <h6 class="company_address">
                                    {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                    {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                </h6>

                                @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                    <p><strong>Phone :</strong> {{ $sale->branch->phone }}</p>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                    <p><strong>Email :</strong> {{ $sale->branch->email }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                @for ($i = 0; $i < $sale->branch->add_sale_invoice_layout->gap_from_top; $i++)
                    </br>
                @endfor
            @endif

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($sale->branch->add_sale_invoice_layout->customer_address)
                                <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_tax_no)
                                <li><strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_phone)
                                <li><strong>Phone : </strong> >{{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4 text-center">
                        @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->add_sale_invoice_layout->invoice_heading }}</h5>
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
                        <img style="width: 170px; height:40px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No : </strong> {{ $sale->invoice_id }}</li>
                            <li><strong> Date : {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' .date($timeFormat ,strtotime($sale->time)) }}</strong></li>
                            <li><strong> Entered By : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table  modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">Descrpiton</th>
                            <th class="text-start">Sold Qty</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                <th class="text-start">Warranty</th>
                            @endif
                            <th class="text-start">Price</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                <th class="text-start">Discount</th>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->product_tax)
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
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    
                                    {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                    <td class="text-start">
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warranty' : 'Guaranty' }}
                                            {!! $sale->branch->add_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @else 
                                            <strong>No</strong>
                                        @endif
                                    </td>
                                @endif

                                <td class="text-start">{{ $sale_product->unit_price_inc_tax }} </td>

                                @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                    <td class="text-start">{{ $sale_product->unit_discount_amount }}</td>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->product_tax)
                                    <td class="text-start">{{ $sale_product->unit_tax_percent }}</td>
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
                    @if ($sale->branch->add_sale_invoice_layout->show_total_in_word == 1)
                        <p style="text-transform: uppercase;"><strong>In Word : <span id="inword"></span> ONLY.</strong></p>
                    @endif
                    <br> 
                    <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                        @if ($sale->branch->add_sale_invoice_layout->account_name)
                            <p>Account Name : {{ $sale->branch->add_sale_invoice_layout->account_name }}</p>
                        @endif

                        @if ($sale->branch->add_sale_invoice_layout->account_no)
                            <p>Account No : {{ $sale->branch->add_sale_invoice_layout->account_no }}</p>
                        @endif

                        @if ($sale->branch->add_sale_invoice_layout->bank_name)
                            <p>Bank : {{ $sale->branch->add_sale_invoice_layout->bank_name }}</p>
                        @endif

                        @if ($sale->branch->add_sale_invoice_layout->bank_branch)
                            <p>Branch : {{ $sale->branch->add_sale_invoice_layout->bank_branch }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <td class="text-end"><strong>Net Total Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="net_total text-end">{{ $sale->net_total_amount }}</td>
                            </tr> 
                            
                            <tr>
                                <td class="text-end"><strong> Order Discount : </strong></td>
                                <td class="order_discount text-end">
                                    <b> 
                                        @if ($sale->order_discount_type == 1)
                                            {{ $sale->order_discount_amount }} (Fixed)
                                        @else
                                            {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                        @endif
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="order_tax text-end">
                                    {{ $sale->order_tax_amount }}({{ $sale->order_tax_percent }} %)
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Shipment charge : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="shipment_charge text-end">
                                    {{ number_format($sale->shipment_charge, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Previous Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_payable text-end">{{ number_format($previous_due, 2) }}</td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Payable : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_payable text-end">
                                    {{ number_format($total_payable_amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_paid text-end">
                                    {{ number_format($paying_amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-end"><strong> Change Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                                <td class="total_paid text-end">
                                    {{ number_format($change_amount > 0 ? $change_amount : 0, 2) }}
                                </td>
                            </tr> 

                            <tr>
                                <td class="text-end"><strong> Total Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                <td class="total_due text-end">
                                    {{ number_format($total_due > 0 ? $total_due : 0, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Customer's signature</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Checked By</strong>  </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Approved By</strong> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="details_area text-center">
                        <p class="borderTop"><strong>Signature Of Authority</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <p>{!! $sale->branch->add_sale_invoice_layout->invoice_notice ? '<b>Attention : <b>' . $sale->branch->add_sale_invoice_layout->invoice_notice : '' !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <p>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</p>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <p><strong>Our Sister Concern</strong></p><br>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:35px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:35px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:35px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:35px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
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
    <div class="sale_print_template">
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5> 
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
                                    <span><b>Phone :</b>  {{ $sale->branch->phone }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span><b>Email :</b> {{ $sale->branch->email }}</span>
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
                                    <b>Date:</b> <span>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</span> 
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
                                    <th class="text-start">{{ $loop->index + 1 }}. {{ $saleProduct->product->name.$variant }} </th>
                                    
                                    <th class="text-center">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center">{{ $saleProduct->unit_price_inc_tax }}</th>
                                    <th class="text-end">{{ $saleProduct->subtotal }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100">
                        <thead>
                            <tr >
                                <th class="text-end">Discount :</th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->order_discount_amount }}
                                    </span>
                                </th>
                            </tr>
                            
                            <tr>
                                <th class="text-end">Order Tax :</th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{-- {{ $sale->order_tax_amount }} --}}
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">Previous Due : </th>
                                <th class="text-end">
                                    <span>
                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ number_format($previous_due, 2) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> Payable : </th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_payable_amount, 2) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> Paid : </th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($paying_amount, 2) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <td class="text-start"><strong> Change Amount : </strong></td>
                                <td class="total_paid text-end">
                                    <b>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ number_format($change_amount > 0 ? $change_amount : 0, 2) }}
                                    </b>
                                </td>
                            </tr> 

                            <tr>
                                <th class="text-end"> Due : </th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_due > 0 ? $total_due : 0, 2) }}
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
                                    <span>{{ $sale->branch->add_sale_invoice_layout->invoice_notice ?  $sale->branch->add_sale_invoice_layout->invoice_notice : '' }}</span> 
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>{{ $sale->branch->add_sale_invoice_layout->footer_text ?  $sale->branch->add_sale_invoice_layout->footer_text : '' }}</span> 
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
                                        <span>Software By <strong>SpeedDigit Pvt. Ltd.</strong> </span>
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