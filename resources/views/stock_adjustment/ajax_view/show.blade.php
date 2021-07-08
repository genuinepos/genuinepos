 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog col-80-modal">
      <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Stock Adjustment Details (Reference No : <strong>{{ $adjustment->invoice_id }}</strong>)</h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        @if ($adjustment->branch)
                            <li>
                                <strong>Adjustment From : </strong> 
                                <b>{{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }}</b>
                            </li>
                            <li>
                                <strong>Adjustment Location : </strong> 
                                {{ $adjustment->branch->city }}, {{ $adjustment->branch->state }},
                                {{ $adjustment->branch->zip_code }}, {{ $adjustment->branch->country }}
                            </li>
                            <li><strong>Phone : </strong> {{ $adjustment->branch->phone }}</li>
                            <li><strong>Email : </strong> {{ $adjustment->branch->email }}</li>
                        @else
                            <li>
                                <strong>Adjustment From : </strong> 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(Head Office)</b>
                            </li>
                            <li>
                                <strong>Adjustment Location : </strong> 
                                {{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(WAREHOUSE),</b>
                                {{ $adjustment->warehouse->address }}
                            </li>
                            <li>
                                <strong>Phone : </strong> 
                                {{ json_decode($generalSettings->business, true)['phone'] }}
                            </li>
                            <li><strong>Email : </strong> {{ json_decode($generalSettings->business, true)['email'] }}</li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li><strong>Date : </strong> {{ $adjustment->date }}</li>
                        <li><strong>Reference No : </strong> {{ $adjustment->invoice_id }}</li>
                        <li><strong>Type : </strong>
                            {!! $adjustment->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>' !!}
                        </li>
                        <li><strong>Created By : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div><br>

            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-primary text-white text-start">
                                <th class="text-start">Product</th>
                                <th class="text-start">Quantity</th>
                                <th class="text-start">Unit Cost Inc.Tax</th>
                                <th class="text-start">SubTotal</th>
                            </tr>
                        </thead>
                        <tbody class="adjustment_product_list">
                            @foreach ($adjustment->adjustment_products as $product)
                                <tr>
                                    @php
                                        $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                                    @endphp
                                    <td class="text-start">{{ $product->product->name.$variant }}</td>
                                    <td class="text-start">{{ $product->quantity.' ('.$product->unit.')' }}</td>
                                    <td class="text-start">
                                        {{ json_decode($generalSettings->business, true)['currency'].' '.$product->unit_cost_inc_tax }}
                                    </td>
                                    <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'].' '.$product->subtotal }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><br>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-start">Net Total Amount</th>
                                <td class="text-start">
                                    <b>
                                        {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->net_total_amount}}
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start">Recovered Amount </th>
                                <td class="text-start">
                                    <b>
                                        {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->recovered_amount }}
                                    </b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div><br>

            <hr class="p-0 m-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Reason : </h6>
                        <p class="reason">{{ $adjustment->reason }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
            <button type="submit" class="c-btn btn_blue print_btn">Print</button>
        </div>
      </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Adjustment print templete-->
<div class="adjustment_print_template d-none">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        @if ($adjustment->branch)
                            <h5 class="branch_name">{{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }}</h5>
                            <small class="address">{{ $adjustment->branch->city }}, {{ $adjustment->branch->state }},
                                {{ $adjustment->branch->zip_code }}, {{ $adjustment->branch->country }}</small><br>
                            <small class="branch_phone"><b>Phone</b> : {{ $adjustment->branch->phone }}</small><br>
                            <small class="branch_email">{{ $adjustment->branch->email }}</small>
                        @else
                            <h5 class="business_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <small class="address">{{ json_decode($generalSettings->business, true)['address'] }}</small><br>
                            <small class="branch_phone"><b>Phone</b> : {{ json_decode($generalSettings->business, true)['phone'] }}</small><br>
                            <small class="branch_email"><b>Email</b> : {{ json_decode($generalSettings->business, true)['email'] }}</small>
                        @endif
                        <h6 class="bill_name">Stock Adjustment Details</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>Date : </strong>{{ $adjustment->date }}</li>
                        <li><strong>Reference No : </strong>{{ $adjustment->invoice_id }}</li>
                        @if ($adjustment->branch)
                            <li><strong>Adjustment Location : </strong>{{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }} <b>(BRANCH)</b></li>
                        @else 
                            <li><strong>Adjustment Location : </strong>{{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(WAREHOUSE)</b></li>
                        @endif
                        
                    </ul>
                </div>
                <div class="col-lg-4">
                    
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-right">
                        <li>
                            <strong>Type : </strong>
                            {{ $adjustment->type == 1 ? 'Normal' : 'Abnormal' }}
                        </li>
                        <li>
                            <strong>Created By : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Cost Inc.Tax</th>
                                <th scope="col">SubTotal</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="adjustment_print_product_list">
                        @foreach ($adjustment->adjustment_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : ''; 
                                @endphp
                                <td>{{ $product->product->name.$variant }}</td>
                                <td>{{ $product->quantity.' ('.$product->unit.')' }}</td>
                                <td>
                                    {{ json_decode($generalSettings->business, true)['currency'].' '.$product->unit_cost_inc_tax }}
                                </td>
                                <td>{{ json_decode($generalSettings->business, true)['currency'].' '.$product->subtotal }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Net Total Amount</th>
                            <td>
                                <b>
                                    {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->net_total_amount}}
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">Recovered Amount </th>
                            <td>
                                <b>
                                    {{ json_decode($generalSettings->business, true)['currency'].' '.$adjustment->recovered_amount }}
                                </b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <br><br>
        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>Authorized Signature</strong></h6>
                </div>
            </div>
        </div>

        @if (env('PRINT_SD_OTHERS') == true)
            <div class="print_footer">
                <div class="text-center">
                    <h6>Software by <b>SpeedDigit Pvt. Ltd.</b></h6>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Adjustment print templete end-->