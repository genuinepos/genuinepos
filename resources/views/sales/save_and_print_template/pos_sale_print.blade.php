{{-- @php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp --}}
<!-- Sale print templete-->
@if ($sale->branch->add_sale_invoice_layout)
    <div class="sale_print_template">
        <div class="details_area">
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $sale->branch->add_sale_invoice_layout->header_text }}</h4>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}
                                    <p/>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}
                                    <p/>
                                <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}
                                    <p/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                <img height="100" width="200"
                                    src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $sale->branch->add_sale_invoice_layout->invoice_heading }}</h1>
                                <h1>
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
                                </h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-right">
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
                                    <h6>Phone : {{ $sale->branch->phone }}</h6>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                    <h6>Phone : {{ $sale->branch->email }}</h6>
                                @endif

                                <h6 class="bill_name">Entered By :
                                    {{ $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div style=" {{ $sale->branch->add_sale_invoice_layout->is_header_less == 1 ? 'margin-top:' . $sale->branch->add_sale_invoice_layout->gap_from_header.'in;' : '' }}  "
                class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer : </strong> <span
                                    class="customer_name">{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                            </li>
                            @if ($sale->branch->add_sale_invoice_layout->customer_address)
                                <li><strong>Address : </strong> <span
                                        class="customer_address">{{ $sale->customer ? $sale->customer->address : '' }}</span>
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_tax_no)
                                <li><strong>Tax Number : </strong> <span
                                        class="customer_tax_number">{{ $sale->customer ? $sale->customer->tax_number : '' }}</span>
                                </li>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->customer_phone)
                                <li><strong>Phone : </strong> <span
                                        class="customer_phone">{{ $sale->customer ? $sale->customer->phone : '' }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No : <span class="invoice_id">{{ $sale->invoice_id }}</span>
                                </strong></li>
                            <li><strong> Date : <span class="date">{{ $sale->date }}</span> </strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th scope="col">Descrpiton</th>
                            <th scope="col">Sold Qty</th>
                            @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                <th scope="col">Warranty</th>
                            @endif

                            <th scope="col">Unit Price</th>

                            @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                <th scope="col">Discount</th>
                            @endif

                            @if ($sale->branch->add_sale_invoice_layout->product_tax)
                                <th scope="col">Tax</th>
                            @endif

                            <th scope="col">SubTotal</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td>
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td>{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                    <td>
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warrantiy' : 'Guaranty' }}
                                            {!! $sale->branch->add_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @endif
                                    </td>
                                @endif

                                <td>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->unit_price_inc_tax }} </td>

                                @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                    <td>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->product_tax)
                                    <td>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale_product->unit_tax_percent }}
                                    </td>
                                @endif

                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @if ($sale->branch->add_sale_invoice_layout->show_total_in_word == 1)
                        <p><b>In Word : <span id="inword"></span></b></p>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Net Total Amount :</strong></td>
                                <td class="net_total text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> Order Discount : </strong></td>
                                <td class="order_discount text-left">
                                    <b>@if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> Order Tax : </strong></td>
                                <td class="order_tax text-left">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->order_tax_amount }}
                                    ({{ $sale->order_tax_percent }} %)</b></td>
                            </tr>

                            <tr>
                                <td><strong> Shipment charge : </strong></td>
                                <td class="shipment_charge text-left">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->shipment_charge, 2) }}</b>
                                </td>
                            </tr>

                            @if ($previous_due > 0)
                                <tr>
                                    <td><strong> Previous Due : </strong></td>
                                    <td class="total_payable text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($previous_due, 2) }}</b></td>
                                </tr>

                                <tr>
                                    <td><strong> Total Payable : </strong></td>
                                    <td class="total_payable text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_payable_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Paid : </strong></td>
                                    <td class="total_paid text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($paying_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Change Amount : </strong></td>
                                    <td class="total_paid text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($change_amount > 0 ? $change_amount : 0, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Due : </strong></td>
                                    <td class="total_due text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_due > 0 ? $total_due : 0, 2) }}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td><strong> Total Payable : </strong></td>
                                    <td class="total_payable text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->total_payable_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Paid : </strong></td>
                                    <td class="total_paid text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->paid, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Change Amount : </strong></td>
                                    <td class="total_paid text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->change_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Due : </strong></td>
                                    <td class="total_paid text-left">
                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->due, 2) }}</b>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Recevier's signature </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-right">
                        <h6> Signature Of Authority </h6>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div> --}}
            </div><br><br> 
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <span>{!! $sale->branch->add_sale_invoice_layout->invoice_notice ? '<b>Attention : <b>' . $sale->branch->add_sale_invoice_layout->invoice_notice : '' !!}</span>
                    </div>
                </div>
            </div><br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
    @endphp
    <div class="sale_print_template">
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $defaultLayout->header_text }}</h4>
                                <p>{{ $defaultLayout->sub_heading_1 }}
                                    <p/>
                                <p>{{ $defaultLayout->sub_heading_2 }}
                                    <p/>
                                <p>{{ $defaultLayout->sub_heading_3 }}
                                    <p/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                <img height="100" width="200"
                                    src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $defaultLayout->invoice_heading }}</h1>
                                <h1>
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
                                </h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-right">
                                <h5 class="company_name">
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                <h6 class="company_address">
                                    {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                    {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                    {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                    {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                    {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                </h6>

                                @if ($defaultLayout->branch_phone)
                                    <h6>Phone : {{ $sale->branch->phone }}</h6>
                                @endif

                                @if ($defaultLayout->branch_email)
                                    <h6>Phone : {{ $sale->branch->email }}</h6>
                                @endif

                                <h6 class="bill_name">Entered By :
                                    {{ $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div style=" {{ $defaultLayout->is_header_less == 1 ? 'margin-top:' . $defaultLayout->gap_from_header.'in;' : '' }}  "
                class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer : </strong> <span
                                    class="customer_name">{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li><strong>Address : </strong> <span
                                        class="customer_address">{{ $sale->customer ? $sale->customer->address : '' }}</span>
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li><strong>Tax Number : </strong> <span
                                        class="customer_tax_number">{{ $sale->customer ? $sale->customer->tax_number : '' }}</span>
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li><strong>Phone : </strong> <span
                                        class="customer_phone">{{ $sale->customer ? $sale->customer->phone : '' }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Invoice No : <span class="invoice_id">{{ $sale->invoice_id }}</span>
                                </strong></li>
                            <li><strong> Date : <span class="date">{{ $sale->date }}</span> </strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th scope="col">Descrpiton</th>
                            <th scope="col">Sold Qty</th>
                            @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                <th scope="col">Warranty</th>
                            @endif

                            <th scope="col">Unit Price</th>

                            @if ($defaultLayout->product_discount)
                                <th scope="col">Discount</th>
                            @endif

                            @if ($defaultLayout->product_tax)
                                <th scope="col">Tax</th>
                            @endif

                            <th scope="col">SubTotal</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_print_product_list">
                        @foreach ($sale->sale_products as $sale_product)
                            <tr>
                                <td>
                                    {{ $sale_product->product->name }}
                                    @if ($sale_product->variant)
                                        -{{ $sale_product->variant->variant_name }}
                                    @endif
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td>{{ $sale_product->quantity }} ({{ $sale_product->unit }}) </td>

                                @if ($defaultLayout->product_w_type || $defaultLayout->product_w_duration || $defaultLayout->product_w_discription)
                                    <td>
                                        @if ($sale_product->product->warranty)
                                            {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                            {{ $sale_product->product->warranty->type == 1 ? 'Warrantiy' : 'Guaranty' }}
                                            {!! $defaultLayout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                        @endif
                                    </td>
                                @endif

                                <td>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->unit_price_inc_tax }} </td>

                                @if ($defaultLayout->product_discount)
                                    <td>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale_product->unit_discount_amount }}
                                    </td>
                                @endif

                                @if ($defaultLayout->product_tax)
                                    <td>
                                        {{ $sale_product->unit_tax_percent }}%
                                    </td>
                                @endif

                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale_product->subtotal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @if ($defaultLayout->show_total_in_word == 1)
                        <p><b>In Word : <span id="inword"></span></b></p>
                    @endif
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Net Total Amount :</strong></td>
                                <td class="net_total text-left">
                                    <b></b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->net_total_amount }}</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> Order Discount : </strong></td>
                                <td class="order_discount text-left">
                                </b>@if ($sale->order_discount_type == 1)
                                        {{ $sale->order_discount_amount }} (Fixed)
                                    @else
                                        {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                    @endif</b>
                                </td>
                            </tr>

                            <tr>
                                <td><strong> Order Tax : </strong></td>
                                <td class="order_tax text-left">
                                </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ $sale->order_tax_amount }}
                                    ({{ $sale->order_tax_percent }} %)</b></td>
                            </tr>

                            <tr>
                                <td><strong> Shipment charge : </strong></td>
                                <td class="shipment_charge text-left">
                                </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ number_format($sale->shipment_charge, 2) }}</b>
                                </td>
                            </tr>

                            @if ($previous_due > 0)
                                <tr>
                                    <td><strong> Previous Due : </strong></td>
                                    <td class="total_payable text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($previous_due, 2) }}</b></td>
                                </tr>

                                <tr>
                                    <td><strong> Total Payable : </strong></td>
                                    <td class="total_payable text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_payable_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Paid : </strong></td>
                                    <td class="total_paid text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($paying_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Change Amount : </strong></td>
                                    <td class="total_paid text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($change_amount > 0 ? $change_amount : 0, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Due : </strong></td>
                                    <td class="total_due text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($total_due > 0 ? $total_due : 0, 2) }}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td><strong> Total Payable : </strong></td>
                                    <td class="total_payable text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->total_payable_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Paid : </strong></td>
                                    <td class="total_paid text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->paid, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Change Amount : </strong></td>
                                    <td class="total_paid text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->change_amount, 2) }}</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong> Total Due : </strong></td>
                                    <td class="total_paid text-left">
                                    </b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ number_format($sale->due, 2) }}</b>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Recevier's signature </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-right">
                        <h6> Signature Of Authority </h6>
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div> --}}
            </div><br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice_notice">
                        <span>{!! $defaultLayout->invoice_notice ? '<b>Attention : <b>' . $defaultLayout->invoice_notice : '' !!}</span>
                    </div>
                </div>
            </div><br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $defaultLayout->footer_text }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Sale print templete end-->

<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];

    function convert_millions(num) {
        if (num >= 100000) {
            return convert_millions(Math.floor(num / 100000)) + " Lack " + convert_thousands(num % 1000000);
        } else {
            return convert_thousands(num);
        }
    }

    function convert_thousands(num) {
        if (num >= 1000) {
            return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
        } else {
            return convert_hundreds(num);
        }
    }

    function convert_hundreds(num) {
        if (num > 99) {
            return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
        } else {
            return convert_tens(num);
        }
    }

    function convert_tens(num) {
        if (num < 10) return ones[num];
        else if (num >= 10 && num < 20) return teens[num - 10];
        else {
            return tens[Math.floor(num / 10)] + " " + ones[num % 10];
        }
    }

    function convert(num) {
        if (num == 0) return "zero";
        else return convert_millions(num);
    }

@if ($previous_due > 0)
    document.getElementById('inword').innerHTML = convert(parseInt("{{ $total_payable_amount }}")).toUpperCase()+' ONLY.';  
@else
    document.getElementById('inword').innerHTML = convert(parseInt("{{ $sale->total_payable_amount }}")).toUpperCase()+' ONLY.';  
@endif
   
</script>
