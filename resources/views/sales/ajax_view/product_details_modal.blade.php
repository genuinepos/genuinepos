@php 
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); 
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';                          
@endphp 
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Sale Details (Invoice ID : <strong>
                        <span class="head_invoice_id">{{ $sale->invoice_id }}</span>
                    </strong>)
                </h5>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>Customer :- </strong>
                            </li>

                            <li>
                                <strong>Name : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>

                            <li>
                                <strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li>

                            <li>
                                <strong>Tax Number : </strong> {{ $sale->customer ? $sale->customer->tax_number : '' }}
                            </li>

                            <li>
                                <strong>Phone : </strong>{{ $sale->customer ? $sale->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>Sale From : </strong></li>
                            @if ($sale->branch)
                                <li>
                                    <strong>Stock Location : </strong>
                                    {{ $sale->branch->name }}/{{ $sale->branch->branch_code }}
                                </li>
                                <li>
                                    <strong>Address : </strong>
                                    {{ $sale->branch->city }}, {{ $sale->branch->state }},
                                        {{ $sale->branch->zip_code }}, {{ $sale->branch->country }}
                                </li>
                                <li><strong>Phone : </strong> {{ $sale->branch->phone }}</li> 
                            @else 
                                <li><strong>Stock Location : </strong> 
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                                </li>
                                <li><strong>Address : </strong>{{ json_decode($generalSettings->business, true)['address'] }}</li>
                                <li><strong>Phone : </strong>{{ json_decode($generalSettings->business, true)['phone'] }}</li> 
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li><strong>Date : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . $sale->time }}</li>
                            <li><strong>Invoice ID : </strong> {{ $sale->invoice_id }}</li>
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
                                @php
                                    $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                                @endphp
                                @if ($sale->due <= 0) 
                                    <span class="badge bg-success"> Paid </span>
                                @elseif ($sale->due > 0 && $sale->due < $payable) 
                                    <span class="badge bg-primary text-white">Partial</span>
                                @elseif ($payable == $sale->due)
                                    <span class="badge bg-danger text-white">Due</span>
                                @endif
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
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">S/L</th>
                                    <th class="text-start">Product</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Unit Price Exc.Tax({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">Unit Discount({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">Unit Tax({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th class="text-end">Unit Price Inc.Tax({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    <th sclass="text-end">SubTotal({{ json_decode($generalSettings->business, true)['currency'] }})</th>
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
                                        <td class="text-end">{{ $saleProduct->quantity }}</td>
                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }}
                                        </td>
                                        @php
                                            $DiscountType = $saleProduct->unit_discount_type == 1 ? ' (Fixed)' : ' (' . $saleProduct->unit_discount . '%)';
                                        @endphp
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount) . $DiscountType }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount) . ' (' . $saleProduct->unit_tax_percent . '%)' }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}
                                        </td>
                                        <td class="text-end">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        @if (auth()->user()->permission->sale['sale_payment'] == '1') 
                            @include('sales.ajax_view.partials.add_sale_details_payment_list')
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-start">Net Total Amount :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span class="net_total">
                                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                        </span>
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Order Discount :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        <span class="order_discount">
                                            @php
                                                $discount_type = $sale->order_discount_type == 1 ? ' (Fixed)' : '%';
                                            @endphp
                                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) . $discount_type }}
                                        </span>
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Order Tax :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount) . ' (' . $sale->order_tax_percent . '%)' }}
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Shipment Charge :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Grand Total :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Sale Return :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Total Paid :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </td>
                                </tr>
    
                                <tr>
                                    <th class="text-start">Total Due :</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div> 
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p><b>Shipping Details</b> : </p>
                            <p class="shipping_details">
                                {{ $sale->shipment_details ? $sale->shipment_details : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="details_area">
                            <p><b>Sale Note</b> : </p>
                            <p class="sale_note">{{ $sale->sale_note ? $sale->sale_note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if ($sale->created_by == 1)
                    @if (auth()->user()->permission->sale['edit_add_sale'] == '1') 
                        <a class="btn btn-sm btn-secondary" href="{{ route('sales.edit', $sale->id) }}"> Edit</a>
                    @endif
                @else 
                    @if (auth()->user()->permission->sale['pos_edit'] == '1') 
                        <a class="footer_btn btn btn-sm btn-secondary" class="btn btn-sm btn-secondary" href="{{ route('sales.pos.edit', $sale->id) }}"> Edit</a>
                    @endif
                @endif
                
                @if (auth()->user()->permission->sale['shipment_access'] == '1') 
                    <button type="button" id="print_packing_slip" href="{{ route('sales.packing.slip', $sale->id) }}"
                    class="footer_btn btn btn-sm btn-success">Print Packing Slip</button>
                @endif

                <button type="button" class="footer_btn btn btn-sm btn-info print_challan_btn text-white">Print Challan</button>
                <button type="button" class="footer_btn btn btn-sm btn-primary print_btn">Print Invoice</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Sale print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    @include('sales.ajax_view.partials.add_sale_branch_invoice_layout')
@else
    @include('sales.ajax_view.partials.add_sale_default_lnvoice_layout')
@endif
<!-- Sale print templete end-->

<!-- Challan print templete-->
@if ($sale->branch && $sale->branch->add_sale_invoice_layout)
    @include('sales.ajax_view.partials.add_sale_branch_challan_layout')
@else
    @include('sales.ajax_view.partials.add_sale_default_challan_layout')
@endif
<!-- Challan print templete end-->
<script>
  var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b= ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function inWords (num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return; var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }
    document.getElementById('inword').innerHTML = inWords(parseInt("{{ $sale->total_payable_amount }}"));
</script>