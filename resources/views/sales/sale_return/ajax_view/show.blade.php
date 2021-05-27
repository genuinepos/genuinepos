    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog col-65-modal">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                  Sale return (Invoice ID : <strong>{{ $saleReturn->invoice_id }}</strong>)
              </h5>
              <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>Return Details : </strong> </li>
                            <li><strong>Invoice ID : </strong> {{ $saleReturn->invoice_id }}</li>
                            <li><strong>Return Date : </strong> {{ $saleReturn->date }}</li>
                            <li><strong>Customer Name : </strong> {{ $saleReturn->customer ? $saleReturn->customer->name : 'Walk-In-Customer' }}</li>
                            <li><strong>Stock Location : </strong> {{$saleReturn->branch ? $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code : $saleReturn->warehouse->warehouse_name.'/'.$saleReturn->warehouse->warehouse_code }} </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            
                        </ul>
                    </div>
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>Parent Sale Details : </li>
                            <li><strong>Invoice No : </strong> {{ $saleReturn->sale->invoice_id  }}</li>
                            <li><strong>Date : </strong> {{ $saleReturn->sale->date }}</li>
                        </ul>
                    </div>
                </div><br><br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="text-start">SL</th>
                                        <th class="text-start">Product</th>
                                        <th class="text-start">Unit Price</th>
                                        <th class="text-start">Return Quantity</th>
                                        <th class="text-start">SubTotal</th>
                                    </tr>
                                </thead>
                                <tbody class="sale_return_product_list">
                                    @foreach ($saleReturn->sale_return_products as $sale_return_product)
                                        @if ($sale_return_product->return_qty > 0)
                                            <tr>
                                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                                <td class="text-start">
                                                    {{ $sale_return_product->sale_product->product->name }}
                                                    @if ($sale_return_product->sale_product->variant)
                                                        -{{ $sale_return_product->sale_product->variant->variant_name }}
                                                    @endif
                                                    @if ($sale_return_product->sale_product->variant)
                                                        ({{ $sale_return_product->sale_product->variant->variant_code }})
                                                    @else   
                                                    ({{ $sale_return_product->sale_product->product->product_code }}) 
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    {{ $sale_return_product->sale_product->unit_price_inc_tax  }}
                                                </td>
                                                <td class="text-start">
                                                    {{ $sale_return_product->return_qty }} ({{ $sale_return_product->unit }})
                                                </td>
                                                <td class="text-start">
                                                    {{ $sale_return_product->return_subtotal }} 
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table tabl-sm">
                                <tr>
                                    <th class="text-start">Net Total Amount : </th>
                                    <td class="net_total ">10000.00</td>
                                </tr>

                                <tr>
                                    <th class="text-start">Return Discount : </th>
                                    <td class="return_discount">400.00</td>
                                </tr>

                                <tr>
                                    <th class="text-start">Total Amount : </th>
                                    <td class="total_return_amount">10000.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="c-btn btn_blue print_btn">Print</button>
                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
            </div>
          </div>
        </div>
    </div>
    <!-- Details Modal End-->

    <!-- Sale print templete-->
    <div class="sale_return_print_template d-none">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="heading text-center">
                            @if ($saleReturn->branch)
                                <h5 class="company_name">{{ $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code}}</h5>
                                <p class="company_address">
                                    {{ $saleReturn->branch->city }}, 
                                    {{ $saleReturn->branch->state }}, 
                                    {{ $saleReturn->branch->zip_code }},
                                    {{ $saleReturn->branch->country }},
                                </p>
                                <p class="company_phone">Phone : {{ $saleReturn->branch->phone }}</p>
                            @else
                                <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                                <p class="company_address">Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                            @endif
                            <h6 class="bill_name">Sale Return Invoice</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Return Details : </strong> </li>
                            <li><strong>Invoice ID : </strong>{{ $saleReturn->invoice }}</li>
                            <li><strong>Return Date : </strong>{{ $saleReturn->date }}</li>
                            <li><strong>Customer Name : </strong>{{ $saleReturn->sale->customer ? $saleReturn->sale->customer->name : 'Walk-In-Customer' }}</li>
                            <li><strong>Stock Location : </strong> {{$saleReturn->branch ? $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code : $saleReturn->warehouse->warehouse_name.'/'.$saleReturn->warehouse->warehouse_code }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <li>
                                <strong>Sale Details </strong> </li>
                            <li>
                                <strong>Invoice No : </strong> {{ $saleReturn->sale->invoice_id }}
                            </li>
                            <li><strong>Date : </strong>  {{ $saleReturn->sale->date }} </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <tr>
                                <th class="text-start">SL</th>
                                <th class="text-start">Product</th>
                                <th class="text-start">Unit Price</th>
                                <th class="text-start">Return Quantity</th>
                                <th class="text-start">SubTotal</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_return_print_product_list">
                        @foreach ($saleReturn->sale_return_products as $sale_return_product)
                            @if ($sale_return_product->return_qty > 0)
                                <tr>
                                    <td class="text-start">{{ $loop->index + 1 }}</td>
                                    <td class="text-start">
                                        {{ $sale_return_product->sale_product->product->name }}
                                        @if ($sale_return_product->sale_product->variant)
                                            -{{ $sale_return_product->sale_product->variant->variant_name }}
                                        @endif
                                        @if ($sale_return_product->sale_product->variant)
                                            ({{ $sale_return_product->sale_product->variant->variant_code }})
                                        @else   
                                        ({{ $sale_return_product->sale_product->product->product_code }}) 
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        {{ $sale_return_product->sale_product->unit_price_inc_tax  }}
                                    </td>
                                    <td class="text-start">
                                        {{ $sale_return_product->return_qty }} ({{ $sale_return_product->unit }})
                                    </td>
                                    <td class="text-start">
                                        {{ $sale_return_product->return_subtotal }} 
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-start" colspan="4"><strong>Net Total Amount :</strong></td>
                            <td class="text-start" colspan="2" class="net_total">{{ $saleReturn->net_total_amount }}</td>
                        </tr>
                        <tr>
                            <th class="text-start" colspan="4">Return Discount</th>
                            <td class="text-start" colspan="2" class="return_discount">
                                @if ($saleReturn->return_discount_type == 1)
                                    {{ $saleReturn->return_discount_amount }} (Fixed)
                                @else  
                                    {{ $saleReturn->return_discount_amount }} ({{ $saleReturn->return_discount}}%)
                                @endif
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="text-start" colspan="4">Grand Total</th>
                            <td class="text-start" colspan="2" class="total_return_amount">{{ $saleReturn->total_return_amount }}</td>
                        </tr>
    
                        <tr>
                            <th class="text-start" colspan="4">Total Due</th>
                            <td class="text-start" colspan="2" class="total_due">{{ $saleReturn->sale->sale_return_due }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <br><br>
            <div class="note">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Receiver's Signature</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>Signature Of seller</strong></h6>
                    </div>
                </div>
            </div>

            <div class="note">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>Powered by <strong>SpeedDigit Pvt. Ltd.</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale print templete end-->