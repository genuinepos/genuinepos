 <!-- Sale print templete-->
 <div class="sale_return_print_template">
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
                        <li><strong>Stock Location : </strong> {{$saleReturn->branch ? $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'] }}</li>
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