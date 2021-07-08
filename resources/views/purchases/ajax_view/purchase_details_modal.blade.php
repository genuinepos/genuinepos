@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp 
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-full-display">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                     Purchase Details (Reference ID : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li><strong>Supplier :- </strong></li>
                             <li><strong>Name : </strong> <span
                                     class="supplier_name">{{ $purchase->supplier->name }}</span></li>
                             <li><strong>Address : </strong> <span
                                     class="supplier_address">{{ $purchase->supplier->address }}</span></li>
                             <li><strong>Tax Number : </strong> <span
                                     class="supplier_tax_number">{{ $purchase->supplier->tax_number }}</span></li>
                             <li><strong>Phone : </strong> <span
                                     class="supplier_phone">{{ $purchase->supplier->phone }}</span></li>
                         </ul>
                     </div>
                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>Purchase From : </strong></li>
                             <li>
                                 <strong>Enterprise Name : </strong> <span class="business_name">
                                     @if ($purchase->branch)
                                         {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                     @else
                                         {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head
                                            Office</b>)
                                     @endif
                                 </span>
                             </li>
                             <li><strong>Stored Location : </strong>
                                 <span class="branch_or_warehouse">
                                     @if ($purchase->branch)
                                         {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                         (<b>Branch/Concern</b>) ,<br>
                                         {{ $purchase->branch ? $purchase->branch->city : '' }},
                                         {{ $purchase->branch ? $purchase->branch->state : '' }},
                                         {{ $purchase->branch ? $purchase->branch->zip_code : '' }},
                                         {{ $purchase->branch ? $purchase->branch->country : '' }}.
                                     @else
                                         {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_name }}
                                         (<b>Warehouse</b>),<br>
                                         {{ $purchase->warehouse->address }}.
                                     @endif
                                 </span>
                             </li>
                             <li><strong>Phone : </strong>
                                 <span class="phone">
                                     @if ($purchase->branch)
                                         {{ $purchase->branch->phone }}, <br>
                                     @else
                                         {{ $purchase->warehouse->phone }}
                                     @endif
                                 </span>
                             </li>
                         </ul>
                     </div>
                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>Date : </strong> {{ $purchase->date . ' ' . $purchase->time }}</li>
                             <li><strong>P.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                             <li>
                                <strong>Purchase Status : </strong>
                                @if ($purchase->purchase_status == 1)
                                    <span class="badge bg-success">Received</span>
                                @elseif($purchase->purchase_status == 2){
                                    <span class="badge bg-warning text-white">Pending</span>
                                @else
                                    <span class="badge bg-primary">Ordered</span>
                                @endif
                             </li>
                             <li><strong>Payment Status : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">Paid</span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable) 
                                    <span class="badge bg-primary text-white">Partial</span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">Due</span>
                                @endif
                             </li>
                             <li>
                                 <strong>Created By : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                             </li>
                         </ul>
                     </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="" class="table modal-table table-sm table-striped">
                                 <thead>
                                     <tr class="bg-primary">
                                         <th class="text-white text-start">Product</th>
                                         <th class="text-white text-start">Quantity</th>
                                         <th class="text-white text-start">Unit Cost (Before Discount)</th>
                                         <th class="text-white text-start">Unit Discount</th>
                                         <th class="text-white text-start">Unit Cost (Before Tax)</th>
                                         <th class="text-white text-start">SubTotal (Before Tax)</th>
                                         <th class="text-white text-start">Tax(%)</th>
                                         <th class="text-white text-start">Unit Cost (After Tax)</th>
                                         <th class="text-white text-start">Unit Selling Price</th>
                                         <th class="text-white text-start">Lot Number</th>
                                         <th class="text-white text-start">SubTotal</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchase_products as $product)
                                        <tr>
                                            @php
                                                $variant = $product->variant ? '('.$product->variant->variant_name.')' : ''; 
                                            @endphp
                                            
                                            <td class="text-start">{{ $product->product->name.' '.$variant }}</td>
                                            <td class="text-start">{{ $product->quantity }}</td>
                                            <td class="text-start">
                                                {{ json_decode($generalSettings->business, true)['currency'].''.$product->unit_cost }}
                                            </td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->unit_discount }} </td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->unit_cost_with_discount }}</td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->subtotal }}</td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->net_unit_cost }} </td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->selling_price }}</td>
                                            <td class="text-start">{{ $product->lot_no ? $product->lot_no : '' }}</td>
                                            <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].''.$product->line_total }}</td>
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="payment_table">
                             <div class="table-responsive">
                                <table class="table modal-table table-striped table-sm">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <th>Date</th>
                                            <th>Voucher No</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Type</th>
                                            <th>Account</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                       @if (count($purchase->purchase_payments) > 0)
                                           @foreach ($purchase->purchase_payments as $payment)
                                               <tr data-info="{{ $payment }}">
                                                   <td>{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                                                   <td>{{ $payment->invoice_id }}</td>
                                                   <td>{{json_decode($generalSettings->business, true)['currency'] .' '. $payment->paid_amount }}</td>
                                                   <td>{{ $payment->pay_mode }}</td>
                                                   <td>{{ $payment->payment_type == 1 ? 'Purchase due' : 'Return due' }}</td>
                                                   <td>{{ $payment->account ? $payment->account->name : 'N/A' }}</td>
                                                   <td>
                                                       @if (auth()->user()->branch_id == $purchase->branch_id)
                                                           @if ($payment->payment_type == 1)
                                                               <a href="{{ route('purchases.payment.edit', $payment->id) }}" id="edit_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @else
                                                               <a href="{{ route('purchases.return.payment.edit', $payment->id) }}" id="edit_return_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @endif
                                                           <a href="{{ route('purchases.payment.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                                       @else   
                                                           ......
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
                         <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr >
                                    <th class="text-start">Net Total Amount</th>
                                    <td class="text-start"> <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                           {{ $purchase->net_total_amount }} 
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Purchase Discount</th>
                                    <td class="text-start">
                                       <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                           {{ $purchase->order_discount }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Purchase Tax</th>
                                    <td class="text-start">
                                       <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                           {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Shipment Charge</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                           {{ $purchase->shipment_charge }}
                                   </td>
                                </tr>
   
                                <tr>
                                    <th class="text-start">Purchase Total</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                           {{ $purchase->total_purchase_amount }}
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
                             <p class="shipping_details">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p><b>Purchase Note</b> : </p>
                             <p class="purchase_note">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="c-btn btn_blue print_btn">Print</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Details Modal End-->

 <!-- Purchase print templete-->
    <div class="purchase_print_template d-none">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if ($purchase->branch)
                            <img style="height: 75px; width:200px;" src="{{ asset('public/uploads/branch_logo/' . $purchase->branch->logo) }}">
                        @else 
                            <img style="height: 75px; width:200px;" src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <h1 class="bill_name">Purchase Bill</h1>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        
                    </div>
                </div>
            </div>

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Supplier :- </strong></li>
                            <li><strong>Namne : </strong>{{ $purchase->supplier->name }}</li>
                            <li><strong>Address : </strong>{{ $purchase->supplier->address }}</li>
                            <li><strong>Tax Number : </strong> {{ $purchase->supplier->tax_number }}</li>
                            <li><strong>Phone : </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Purchase From : </strong></li>
                            <li>
                                <strong>Enterprise Name : </strong> 
                                @if ($purchase->branch)
                                    {{ $purchase->branch->name }}
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head
                                        Office</b>)
                                @endif
                            </li>
                            <li><strong>Stored Location : </strong>
                                <span class="branch_or_warehouse">
                                    @if ($purchase->branch)
                                        {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                        (<b>Branch/Concern</b>) ,<br>
                                        {{ $purchase->branch ? $purchase->branch->city : '' }},
                                        {{ $purchase->branch ? $purchase->branch->state : '' }},
                                        {{ $purchase->branch ? $purchase->branch->zip_code : '' }},
                                        {{ $purchase->branch ? $purchase->branch->country : '' }}.
                                    @else
                                        {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_name }}
                                        (<b>Warehouse</b>),<br>
                                        {{ $purchase->warehouse->address }}.
                                    @endif
                                </span>
                            </li>
                            <li><strong>Phone : </strong>
                                <span class="phone">
                                    @if ($purchase->branch)
                                        {{ $purchase->branch->phone }}, <br>
                                    @else
                                        {{ $purchase->warehouse->phone }}
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Date : </strong>{{ $purchase->date . ' ' . $purchase->time }}</li>
                            <li><strong>P.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>Purchase Status : </strong>
                                <span class="purchase_status">
                                    @if ($purchase->purchase_status == 1)
                                        Received
                                    @elseif($purchase->purchase_status == 2){
                                        Pending
                                    @else
                                        Ordered
                                    @endif
                                </span>
                            </li>
                            <li><strong>Payment Status : </strong>
                               @php
                                   $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                               @endphp
                               @if ($purchase->due <= 0)
                                   Paid
                               @elseif($purchase->due > 0 && $purchase->due < $payable) 
                                   Partial
                               @elseif($payable == $purchase->due)
                                   Due
                               @endif
                            </li>
                            <li><strong>Created By : </strong>
                                   {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Cost (Before Discount)</th>
                            <th scope="col">Unit Discount</th>
                            <th scope="col">Unit Cost (Before Tax)</th>
                            <th scope="col">SubTotal (Before Tax)</th>
                            <th scope="col">Tax(%)</th>
                            <th scope="col">Unit Cost (After Tax)</th>
                            <th scope="col">Unit Selling Price</th>
                            <th scope="col">Lot Number</th>
                            <th scope="col">SubTotal</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchase_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                                @endphp
                                
                                <td>{{ $product->product->name.' '.$variant }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'].$product->unit_cost }}
                                </td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->unit_discount }} </td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->unit_cost_with_discount }}</td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->subtotal }}</td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->net_unit_cost }} </td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->selling_price }}</td>
                                <td>{{ $product->lot_no ? $product->lot_no : '' }}</td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].$product->line_total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-start"><strong>Net Total Amount :</strong></td>
                            <td colspan="2" class="text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                    {{ $purchase->net_total_amount }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-start">Purchase Discount</th>
                            <td colspan="2" class="text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                {{ $purchase->order_discount }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-start">Purchase Tax</th>
                            <td colspan="2" class="text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="9" class="text-start">Shipment Charge</th>
                            <td colspan="2" class="text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                {{ $purchase->shipment_charge }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="9" class="text-start">Purchase Total</th>
                            <td colspan="2" class="text-start">
                                <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> 
                                {{ $purchase->total_purchase_amount }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>CHECKED BY : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>APPREVED BY : </h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$purchase->invoice_id}}</p>
                </div>
            </div>

            @if (env('PRINT_SD_PURCHASE') == true)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>Software By <b>SpeedDigit Pvt. Ltd.</b></small>
                    </div>
                </div>
            @endif
        </div>
    </div>
 <!-- Purchase print templete end-->
