 @php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp 
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Sale Details (Invoice ID : <strong><span
                            class="head_invoice_id">{{ $sale->invoice_id }}</span></strong>)
                </h5>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>Customer :- </strong></li>
                            <li><strong>Name : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li>
                            <li><strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                            </li>
                            <li><strong>Phone : </strong>{{ $sale->customer ? $sale->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>Sale From : </strong></li>
                            @if ($sale->branch)
                                <li><strong>Business Name : </strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}
                                </li>
                                <li><strong>Address : </strong>{{ $sale->branch->name }}/{{ $sale->branch->branch_code }},
                                        {{ $sale->branch->city }}, {{ $sale->branch->state }},
                                        {{ $sale->branch->zip_code }}, {{ $sale->branch->country }}</li>
                                <li><strong>Phone : </strong> {{ $sale->branch->phone }}</li> 
                            @else 
                                <li><strong>Business Name : </strong>{{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                                </li>
                                <li><strong>Address : </strong>{{ json_decode($generalSettings->business, true)['address'] }}</li>
                                <li><strong>Phone : </strong>{{ json_decode($generalSettings->business, true)['phone'] }}</li> 
                                <li><strong>Stock Location : </strong> 
                                    {{ $sale->warehouse->warehouse_name.'/'.$sale->warehouse->warehouse_code }},
                                    {{ $sale->warehouse->address }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>Date : </strong>{{ $sale->date . ' ' . $sale->time }}</li>
                            <li><strong>Invoice ID : </strong> {{ $sale->invoice_id }}
                            </li>
                            <li><strong>Sale Status : </strong>
                                @if ($sale->status == 1)
                                    <spna class="badge bg-success">Final</spna>
                                @elseif($sale->status == 2)
                                    <spna class="badge bg-primary">Draft</spna>
                                @elseif($sale->status == 3)
                                    <spna class="badge bg-info">Quotation</spna>
                                @endif
                            </li>
                            <li><strong>Payment Status : </strong>
                                <span class="payment_status">
                                    @php
                                        $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                    @endphp
                                    @if ($sale->due <= 0) {
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($sale->due > 0 && $sale->due < $payable) 
                                        <span class="badge bg-primary text-white">Partial</span>
                                    @elseif ($payable == $sale->due)
                                        <span class="badge bg-danger text-white">Due</span>
                                    @endif
                                </span>
                            </li>
                            <li><strong>Shipment Status : </strong>
                                @if ($sale->shipment_status == null)
                                    <spna class="badge bg-danger">Not-Available</spna>
                                @elseif($sale->shipment_status == 1)
                                    <spna class="badge bg-warning">Ordered</spna>
                                @elseif($sale->shipment_status == 2)
                                    <spna class="badge bg-secondary">Packed</spna>
                                @elseif($sale->shipment_status == 3)
                                    <spna class="badge bg-primary">Shipped</spna>
                                @elseif($sale->shipment_status == 4)
                                    <spna class="badge bg-success">Delivered</spna>
                                @elseif($sale->shipment_status == 5)
                                    <spna class="badge bg-info">Cancelled</spna>
                                @endif
                            </li>
                            <li><strong>Created By : </strong>
                                @php
                                    $admin_role = '';
                                    $prefix = '';
                                    $name = $lastName = '';
                                    if ($sale->admin) {
                                        if ($sale->admin->role_type == 1) {
                                            $admin_role = ' (Super-Admin)';
                                        } elseif ($sale->admin->role_type == 2) {
                                            $admin_role = ' (Admin)';
                                        } elseif ($sale->admin->role_type == 3) {
                                            $admin_role = '(' . $sale->admin->role->name . ')';
                                        }
                                    
                                        $prefix = $sale->admin ? $sale->admin->prefix : '';
                                        $name = $sale->admin ? $sale->admin->name : '';
                                        $lastName = $sale->admin ? $sale->admin->last_name : '';
                                    }
                                @endphp
                                {{ $admin_role ? $prefix . ' ' . $name . ' ' . $lastName . $admin_role : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div><br><br>

                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">S/L</th>
                                    <th class="text-start">Product</th>
                                    <th class="text-start">Quantity</th>
                                    <th class="text-start">Unit Price Exc.Tax</th>
                                    <th class="text-start">Unit Discount</th>
                                    <th class="text-start">Unit Tax</th>
                                    <th class="text-start">Unit Price Inc.Tax</th>
                                    <th sclass="text-start">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @foreach ($sale->sale_products as $saleProduct)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start">{{ $saleProduct->product->name . $variant }}</td>
                                        <td class="text-start">{{ $saleProduct->quantity }}</td>
                                        <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_price_exc_tax }}
                                        </td>
                                        @php
                                            $DiscountType = $saleProduct->unit_discount_type == 1 ? ' (Fixed)' : ' (' . $saleProduct->unit_discount . '%)';
                                        @endphp
                                        <td class="text-start">
                                            {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_discount_amount . $DiscountType }}
                                        </td>
                                        <td class="text-start">
                                            {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_tax_amount . ' (' . $saleProduct->unit_tax_percent . '%)' }}
                                        </td>
                                        <td class="text-start">
                                            {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->unit_price_inc_tax }}
                                        </td>
                                        <td class="text-start">
                                            {{ json_decode($generalSettings->business, true)['currency'] . $saleProduct->subtotal }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_table">
                            <div class="table-responsive">
                                <table class="table modal-table table-sm table-striped custom-table">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th class="text-start">Date</th>
                                            <th class="text-start">Invoice ID</th>
                                            <th class="text-start">Amount</th>
                                            <th class="text-start">Account</th>
                                            <th class="text-start">Method</th>
                                            <th class="text-start">Type</th>
                                            <th class="text-start">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                        @if (count($sale->sale_payments) > 0)
                                            @foreach ($sale->sale_payments as $payment)
                                                <tr data-info="{{ $payment }}">
                                                    <td class="text-start">{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                                                    <td class="text-start">{{ $payment->invoice_id }}</td>
                                                    <td class="text-start">
                                                        {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid_amount }}
                                                    </td>
                                                    <td class="text-start">{{ $payment->account ? $payment->account->name : '----' }}</td>
                                                    <td class="text-start">{{ $payment->pay_mode }}</td>
                                                    <td class="text-start">{{ $payment->payment_type == 1 ? 'Sale due' : 'Return due' }}
                                                    </td>
                                                    
                                                    <td class="text-start">
                                                        @if (auth()->user()->branch_id == $sale->branch_id)
                                                            @if ($payment->payment_type == 1)
                                                                <a href="{{ route('sales.payment.edit', $payment->id) }}"
                                                                    id="edit_payment" class="btn-sm"><i
                                                                        class="fas fa-edit text-info"></i></a>
                                                            @else
                                                                <a href="{{ route('sales.return.payment.edit', $payment->id) }}"
                                                                    id="edit_return_payment" class="btn-sm"><i
                                                                        class="fas fa-edit text-info"></i></a>
                                                            @endif
    
                                                            <a href="{{ route('sales.payment.details', $payment->id) }}"
                                                                id="payment_details" class="btn-sm"><i
                                                                    class="fas fa-eye text-primary"></i></a>
                                                            <a href="{{ route('sales.payment.delete', $payment->id) }}"
                                                                id="delete_payment" class="btn-sm"><i
                                                                    class="far fa-trash-alt text-danger"></i></a>
                                                        @else
                                                            ............
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">No Data Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-start">Net Total Amount</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="net_total">
                                        {{ $sale->net_total_amount }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Order Discount</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="order_discount">
                                        @php
                                            $discount_type = $sale->order_discount_type == 1 ? ' (Fixed)' : '%';
                                        @endphp
                                        {{ $sale->order_discount_amount . $discount_type }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Order Tax</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="order_tax">
                                        {{ $sale->order_tax_amount . ' (' . $sale->order_tax_percent . '%)' }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Shipment Charge</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="shipment_charge">
                                        {{ $sale->shipment_charge }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Grand Total</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="total_payable_amount">
                                        {{ $sale->total_payable_amount }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Sale Return</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="sale_return_amount">
                                        {{ $sale->sale_return_amount }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Total Paid</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="total_paid">
                                        {{ $sale->paid }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start">Total Due</th>
                                <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                    <span class="total_due">
                                        {{ $sale->due }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> <br> <br>
                <hr class="p-0 m-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <h6>Shipping Details : </h6>
                            <p class="shipping_details">
                                {{ $sale->shipment_details ? $sale->shipment_details : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <h6>Sale Note : </h6>
                            <p class="sale_note">{{ $sale->sale_note ? $sale->sale_note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="print_packing_slip" href="{{ route('sales.packing.slip', $sale->id) }}"
                    class="btn btn-sm btn-success">Print Packing Slip</button>
                <button type="button" class="btn btn-sm btn-info print_challan_btn">Print Challan</button>
                <button type="button" class="btn btn-sm btn-primary print_btn">Print Invoice</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Sale print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    @if ($sale->branch->add_sale_invoice_layout->layout_design == 1)
        <div class="sale_print_template d-none">
            <div class="details_area">
                @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                    <div class="heading_area">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header_text text-center">
                                    <p>{{ $sale->branch->add_sale_invoice_layout->header_text }}</p>
                                    <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}</p>
                                    <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}</p>
                                    <p>{{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                    @if ($sale->branch)
                                        <img style="height: 70px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else 
                                        <img style="height: 70px; width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                                    @endif
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
                                    @if ($sale->branch)
                                        <h6 class="company_name">
                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                                        <p class="company_address">
                                            <b>
                                                {{ $sale->branch->name . '/' . $sale->branch->branch_code }} <br>
                                                {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                                {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                                {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                                {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                            </b>
                                        </p>

                                        @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                            <p><b>Phone</b> : {{ $sale->branch->phone }}</p>
                                        @endif

                                        @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                            <p><b>Email</b> : {{ $sale->branch->email }}</p>
                                        @endif
                                    @else 
                                        <h5 class="company_name">
                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                        <p class="company_address">
                                            {{ json_decode($generalSettings->business, true)['shop_name'] }},<br>
                                        </p>

                                        @if ($sale->branch->add_sale_invoice_layout->branch_phone)
                                            <p>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                        @endif

                                        @if ($sale->branch->add_sale_invoice_layout->branch_email)
                                            <p>Email : {{ json_decode($generalSettings->business, true)['email'] }}</p>
                                        @endif
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
                                    <li>
                                        <strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                    </li>
                                @endif

                                @if ($sale->branch->add_sale_invoice_layout->customer_phone)
                                    <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-4">
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
                        </div>
                        <div class="col-lg-4">
                            <ul class="list-unstyled">
                                <li><strong> Invoice No :</strong> {{ $sale->invoice_id }}</li>
                                <li><strong> Date : </strong>{{ $sale->date . ' ' . $sale->time }} </li>
                                <li><strong> Entered By : </strong> {{ $sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
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
                                        @if ($sale_product->variant)
                                            ({{ $sale_product->variant->variant_code }})
                                        @else
                                            ({{ $sale_product->product->product_code }})
                                        @endif
                                        {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . ($sale_product->description == 'null' ? '' : $sale_product->description) . '</small>' : '' !!}
                                    </td>
                                    <td class="text-start">{{ $sale_product->quantity }}({{ $sale_product->unit }})</td>

                                    @if ($sale->branch->add_sale_invoice_layout->product_w_type || $sale->branch->add_sale_invoice_layout->product_w_duration || $sale->branch->add_sale_invoice_layout->product_w_discription)
                                        <td class="text-start">
                                            @if ($sale_product->product->warranty)
                                                {{ $sale_product->product->warranty->duration . ' ' . $sale_product->product->warranty->duration_type }}
                                                {{ $sale_product->product->warranty->type == 1 ? 'Warrantiy' : 'Guaranty' }}
                                                {!! $sale->branch->add_sale_invoice_layout->product_w_discription ? '<br><small class="text-muted">' . $sale_product->product->warranty->description . '</small>' : '' !!}
                                            @else 
                                                <b>No</b>
                                            @endif
                                        </td>
                                    @endif

                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->unit_price_inc_tax }}
                                    </td>

                                    @if ($sale->branch->add_sale_invoice_layout->product_discount)
                                        <td class="text-start">
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ $sale_product->unit_discount_amount }}
                                        </td>
                                    @endif

                                    @if ($sale->branch->add_sale_invoice_layout->product_tax)
                                        <td class="text-start">
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ $sale_product->unit_tax_percent }}
                                        </td>
                                    @endif

                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->subtotal }}
                                    </td>
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
                        @if ($sale->branch->add_sale_invoice_layout->show_total_in_word)
                            <p><b>In Word : <span id="inword"></span></b></p>
                        @endif
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px; margin-top:13px;">
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
                                {{-- <tr>
                                    <td class="text-start"><strong>Net Total Amount :</strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                            {{ $sale->net_total_amount }}</b>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="text-start"><strong> Order Discount : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            @if ($sale->order_discount_type == 1)
                                                {{ $sale->order_discount_amount }} (Fixed)
                                            @else
                                                {{ $sale->order_discount_amount }} ( {{ $sale->order_discount }}%)
                                            @endif
                                        <b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Order Tax : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ $sale->order_tax_amount }}
                                            ({{ $sale->order_tax_percent }} %)
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Shipment charge : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->shipment_charge, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Total Payable : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->total_payable_amount, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Total Paid : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->paid, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                {{-- <tr>
                                    <td class="text-start"><strong> Change Amount : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                            {{ number_format($sale->change_amount, 2) }}
                                        </b>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="text-start"><strong> Total Due : </strong></td>
                                    <td class="total_paid text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->due, 2) }}
                                        </b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><br/><br/>

                <div class="row">
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Customer's signature</b>  </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Checked By</b>  </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Approved By</b> </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Signature Of Authority</b></p>
                        </div>
                    </div>
                </div><br/>

                {{-- <div class="row">
                    <div class="barcode text-center">
                        <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                    </div> 
                </div><br>--}}

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
                            <span>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</span>
                        </div>
                    </div>
                </div>

                <div id="footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <p class="m-0 p-0"><b>Our Sister Concern</b></p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                            </div>
                        </div>
        
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                            </div>
                        </div>
        
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <small>Print Date : {{ date('d/m/Y') }}</small>
                        </div>
                        <div class="col-md-6 text-center">
                            <small>Print Time : {{ date('h:i:s') }}</small>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <small>Software By <b>SpeedDigit Pvt. Ltd.</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <style>@page{margin: 8px;}</style>
        <div class="sale_print_template d-none">
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
                                        <b>Date:</b> <span>{{ $sale->date.' '.$sale->time }}</span> 
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
                        <table class="w-100 float-end">
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
                                <th class="text-end"> Total Payable : </th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->total_payable_amount }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Total Paid : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale->paid }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Change Amount : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->change_amount }}
                                    </span>
                                </th>
                            </tr> 

                            <tr>
                                <th class="text-end"><strong> Total Due : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->due }}
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

                                <tr>
                                    <th class="text-center">
                                        <span>SoftWare By <b>SpeedDigit Pvt. Ltd.</b> </span>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    @php
        $defaultLayout = DB::table('invoice_layouts')
            ->where('is_default', 1)
            ->first();
    @endphp
    @if ($defaultLayout->layout_design == 1)
        <div class="sale_print_template d-none">
            <div class="details_area">
                @if ($defaultLayout->is_header_less == 0)
                    <div id="header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header_text text-center">
                                    <p>{{ $defaultLayout->header_text }}</p>
                                    <p>{{ $defaultLayout->sub_heading_1 }}<p/>
                                    <p>{{ $defaultLayout->sub_heading_2 }}<p/>
                                    <p>{{ $defaultLayout->sub_heading_3 }}<p/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                @if ($defaultLayout->show_shop_logo == 1)
                                    @if ($sale->branch)
                                        <img style="height: 75px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                                    @else 
                                        <img style="height: 75px; width:200px;" src="{{ asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
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
                                        <h6 class="company_name">
                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                                        <p class="company_address">
                                            <strong>
                                                {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                                {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                                                {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                                                {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                                {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                                            </strong>
                                        </p>

                                        @if ($defaultLayout->branch_phone)
                                            <p><b>Phone</b> : {{ $sale->branch->phone }}</p>
                                        @endif

                                        @if ($defaultLayout->branch_email)
                                            <p><b>Email</b> : {{ $sale->branch->email }}</p>
                                        @endif
                                    @else
                                        <p class="company_name">
                                            <strong> {{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
                                        <p class="company_address">
                                            {{ json_decode($generalSettings->business, true)['address'] }}
                                        </p>

                                        @if ($defaultLayout->branch_phone)
                                            <p><b>Phone</b> : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                        @endif

                                        @if ($defaultLayout->branch_email)
                                            <p><b>Email</b> : {{ json_decode($generalSettings->business, true)['email'] }}</p>
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

                <div style=" {{ $defaultLayout->is_header_less == 1 ? 'margin-top:' . $defaultLayout->gap_from_top : '' }}  "
                    class="purchase_and_deal_info pt-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <ul class="list-unstyled">
                                <li><strong>Customer : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                                </li>
                                @if ($defaultLayout->customer_address)
                                    <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                    </li>
                                @endif

                                @if ($defaultLayout->customer_tax_no)
                                    <li><strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                    </li>
                                @endif

                                @if ($defaultLayout->customer_phone)
                                    <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
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
                                            Paid
                                        @elseif ($sale->due > 0 && $sale->due < $payable) 
                                            Partial 
                                        @elseif($payable==$sale->due)
                                            Due
                                        @endif
                                    </h6>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            <ul class="list-unstyled">
                                <li><strong> Invoice No : </strong> {{ $sale->invoice_id }}</li>
                                <li><strong> Date : </strong> {{ $sale->date . ' ' . $sale->time }}</li>
                                <li><strong> Entered By : </strong> {{ $sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
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
                                    <th scope="col">Warranty</th>
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
                                    <td class="text-start">{{ $loop->index + 1}} </td>
                                    <td class="text-start">
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

                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->unit_price_inc_tax }} </td>

                                    @if ($defaultLayout->product_discount)
                                        <td class="text-start">
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ $sale_product->unit_discount_amount }}
                                        </td>
                                    @endif

                                    @if ($defaultLayout->product_tax)
                                        <td class="text-start">
                                            {{ $sale_product->unit_tax_percent }}%
                                        </td>
                                    @endif

                                    <td class="text-start">
                                        {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                        {{ $sale_product->subtotal }}
                                    </td>
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
                            <p><b>In Word : <span id="inword"></span></b></p>
                        @endif
                        <br>
                        <div class="bank_details" style="width:100%; border:1px solid black;padding:2px 3px;">
                            @if ($defaultLayout->account_name)
                                <p>Account Name : {{ $defaultLayout->account_name  }}</p>
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
                    </div>
                    <div class="col-md-6">
                        <table class="table modal-table table-sm">
                            <tbody>
                                {{-- <tr>
                                    <td class="text-start"><strong>Net Total Amount :</strong></td>
                                    <td class="net_total text-end">
                                        <b>
                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                            {{ $sale->net_total_amount }}
                                        </b>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="text-start"><strong> Order Discount : </strong></td>
                                    <td class="text-end">
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
                                    <td class="text-start"><strong> Order Tax : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ $sale->order_tax_amount }}
                                            ({{ $sale->order_tax_percent }} %)
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Shipment charge : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->shipment_charge, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Total Payable : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->total_payable_amount, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-start"><strong> Total Paid : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->paid, 2) }}
                                        </b>
                                    </td>
                                </tr>

                                {{-- <tr>
                                    <td class="text-start"><strong> Change Amount : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{ json_decode($generalSettings->business, true)['currency'] }} 
                                            {{ number_format($sale->change_amount, 2) }}
                                        </b>
                                    </td>
                                </tr> --}}

                                <tr>
                                    <td class="text-start"><strong> Total Due : </strong></td>
                                    <td class="text-end">
                                        <b>
                                            {{-- {{ json_decode($generalSettings->business, true)['currency'] }} --}}
                                            {{ number_format($sale->due, 2) }}
                                        </b>
                                    </td>
                                </tr>
        
                            </tbody>
                        </table>
                    </div>
                </div><br><br>

                <div class="row">
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Customer's signature</b></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Checked By</b></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Approved By</b></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="details_area text-center">
                            <p class="borderTop"><b>Signature Of Authority</b></p>
                        </div>
                    </div>
                </div><br>

                {{-- <div class="row">
                    <div class="barcode text-center">
                        <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}"> 
                    </div> 
                </div><br>--}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="invoice_notice">
                            <p>{!! $defaultLayout->invoice_notice ? '<b>Attention : <b>' . $defaultLayout->invoice_notice : '' !!}</p>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center">
                                <h6><b>Our Sister Concern</b></h6>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                            </div>
                        </div>
        
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                            </div>
                        </div>
        
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image_area text-center">
                                <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <small>Print Date : {{ date('d/m/Y') }}</small>
                        </div>
                        <div class="col-md-6 text-center">
                            <small>Print Time : {{ date('h:i:s') }}</small>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else 
        <style>@page{margin: 8px;}</style>
        <!-- Packing slip print templete-->
        <div class="sale_print_template d-none">
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
                    
                    <div class="customer_info mt-3">
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <b>Date:</b> <span>{{ $sale->date.' '.$sale->time }}</span> 
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
                        <table class="w-100 float-end">
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
                                <th class="text-end"> Total Payable : </th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->total_payable_amount }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Total Paid : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ $sale->paid }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"><strong> Change Amount : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->change_amount }}
                                    </span>
                                </th>
                            </tr> 

                            <tr>
                                <th class="text-end"><strong> Total Due : </strong></th>
                                <th class="text-end">
                                    <span>
                                        {{ json_decode($generalSettings->business, true)['currency'] }} 
                                        {{ $sale->due }}
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

                                <tr>
                                    <th class="text-center">
                                        <span>SoftWare By <b>SpeedDigit Pvt. Ltd.</b> </span>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
<!-- Sale print templete end-->

<!-- Challan print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    <div class="challan_print_template d-none">
        <div class="details_area">
            @if ($sale->branch->add_sale_invoice_layout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $sale->branch->add_sale_invoice_layout->header_text }}</h4>
                                <p>
                                    {{ $sale->branch->add_sale_invoice_layout->sub_heading_1 }}
                                <p/>
                                <p>
                                    {{ $sale->branch->add_sale_invoice_layout->sub_heading_2 }}
                                <p/>
                                <p>
                                    {{ $sale->branch->add_sale_invoice_layout->sub_heading_3 }}
                                <p/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($sale->branch->add_sale_invoice_layout->show_shop_logo == 1)
                                <img style="height: 75px; width:200px;"
                                    src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $sale->branch->add_sale_invoice_layout->challan_heading }}</h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                <h3 class="company_name">
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
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
                                    <h6>Eamil : {{ $sale->branch->email }}</h6>
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
                                <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($sale->branch->add_sale_invoice_layout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $sale->branch->add_sale_invoice_layout->challan_heading }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Challan No : </strong> {{ $sale->invoice_id }}
                                </li>
                            <li><strong> Date : </strong> {{ $sale->date.'  '.$sale->time }} </li>
                            <li><strong> Entered By : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">SL</th>
                            <th class="text-start">Product</th>
                            <th class="text-start">Unit</th>
                            <th class="text-start"">Quantity</th>
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
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $sale->branch->add_sale_invoice_layout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->unit }}</td>
                                <td class="text-start">{{ $sale_product->quantity }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><br>

            {{--<div class="row">
                 <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div> 
            </div><br>--}}

            @if (count($sale->sale_products) > 11)
                <br>
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>Continued To this next page....</em></h6>
                    </div>
                </div>
            @endif
           
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Recevier's signature </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-end">
                        <h6> Signature Of Authority </h6>
                    </div>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $sale->branch->add_sale_invoice_layout->footer_text }}</span>
                    </div>
                </div>
            </div><br>

            <div id="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <h6><b>Our Sister Concern</b></h6>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 text-center">
                        <small>Print Date : {{ date('d/m/Y') }}</small>
                    </div>
                    <div class="col-md-6 text-center">
                        <small>Print Time : {{ date('h:i:s') }}</small>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>Software By <b>SpeedDigit Pvt. Ltd.</small></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@else
    @php
        $defaultLayout = DB::table('invoice_layouts')->where('is_default', 1)->first();
    @endphp
    <div class="challan_print_template d-none">
        <div class="details_area">
            @if ($defaultLayout->is_header_less == 0)
                <div class="heading_area">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_text text-center">
                                <h4>{{ $defaultLayout->header_text }}</h4>
                                <p>{{ $defaultLayout->sub_heading_1 }}<p/>
                                <p>{{ $defaultLayout->sub_heading_2 }}<p/>
                                <p>{{ $defaultLayout->sub_heading_3 }}<p/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            @if ($defaultLayout->show_shop_logo == 1)
                                @if ($sale->branch)
                                    <img style="height: 75px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $sale->branch->logo) }}">
                                @else
                                    <img style="height: 75px; width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="middle_header_text text-center">
                                <h1>{{ $defaultLayout->challan_heading }}</h1>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <div class="heading text-end">
                                @if ($sale->branch)
                                    <h3 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                                    <h6 class="company_address">
                                        {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                                        {{ $sale->branch->add_sale_invoice_layout->branch_city == 1 ? $sale->branch->city : '' }},
                                        {{ $sale->branch->add_sale_invoice_layout->branch_state == 1 ? $sale->branch->state : '' }},
                                        {{ $sale->branch->add_sale_invoice_layout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                                        {{ $sale->branch->add_sale_invoice_layout->branch_country == 1 ? $sale->branch->country : '' }}.
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6>Phone : {{ $sale->branch->phone }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6>email : {{ $sale->branch->email }}</h6>
                                    @endif 
                                @else
                                    <h3 class="company_name">
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}</h3>
                                    <h6 class="company_address">
                                        {{ json_decode($generalSettings->business, true)['address'] }}
                                    </h6>

                                    @if ($defaultLayout->branch_phone)
                                        <h6>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</h6>
                                    @endif

                                    @if ($defaultLayout->branch_email)
                                        <h6>Email : {{ json_decode($generalSettings->business, true)['email'] }}</h6>
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
                            <li><strong>Customer : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>
                            @if ($defaultLayout->customer_address)
                                <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_tax_no)
                                <li><strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                                </li>
                            @endif

                            @if ($defaultLayout->customer_phone)
                                <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        @if ($defaultLayout->is_header_less == 1)
                            <div class="middle_header_text text-center">
                                <h5>{{ $defaultLayout->challan_heading }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> Challan No : </strong> {{ $sale->invoice_id }}
                                </li>
                            <li><strong> Date : </strong> {{ $sale->date.' '.$sale->time }} </li>
                            <li><strong> Entered By : </strong> {{$sale->admin ? $sale->admin->prefix . ' ' . $sale->admin->name . ' ' . $sale->admin->last_name : 'N/A' }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                        <tr>
                            <th class="text-start">Serial</th>
                            <th class="text-start">Descrpiton</th>
                            <th class="text-start">Unit</th>
                            <th class="text-start">Quantity</th>
                        </tr>
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
                                    @if ($sale_product->variant)
                                        ({{ $sale_product->variant->variant_code }})
                                    @else
                                        ({{ $sale_product->product->product_code }})
                                    @endif
                                    {!! $defaultLayout->product_imei == 1 ? '<br><small class="text-muted">' . $sale_product->description . '</small>' : '' !!}
                                </td>
                                <td class="text-start">{{ $sale_product->unit }}</td>
                                <td class="text-start">{{ $sale_product->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><br><br>

            @if (count($sale->sale_products) > 11)
                <div class="row page_break">
                    <div class="col-md-12 text-end">
                        <h6><em>Continued To this next page....</em></h6>
                    </div>
                </div>
            @endif

            {{--<div class="row">
                 <div class="barcode text-center">
                    <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($sale->invoice_id, $generatorPNG::TYPE_CODE_128)) }}">
                </div> 
            </div><br>--}}
            
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Recevier's signature </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_area text-end">
                        <h6> Signature Of Authority </h6>
                    </div>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-12">
                    <div class="footer_text text-center">
                        <span>{{ $defaultLayout->footer_text }}</span>
                    </div>
                </div>
            </div>

            <div id="footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading text-center">
                            <h6><b>Our Sister Concern</b></h6>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Nomhost logo.png') }}">
                        </div>
                    </div>
    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Creative Studio.png') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/Speeddigitposprologo.png') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="image_area text-center">
                            <img style="width: 130px; height:40px;" src="{{ asset('public/uploads/layout_concern_logo/UltimateERPLogo.png') }}">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 text-center">
                        <small>Print Date : {{ date('d/m/Y') }}</small>
                    </div>
                    <div class="col-md-6 text-center">
                        <small>Print Time : {{ date('h:i:s') }}</small>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Challan print templete end-->
<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen','nineteen'
    ];

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

    document.getElementById('inword').innerHTML = convert(parseInt("{{ $sale->total_payable_amount }}")).replace(
        'undefined', '(some Penny)').toUpperCase() + ' ONLY.';
</script>