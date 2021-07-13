@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp 
<!-- purchase print templete-->
<div class="purchase_return_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        @if ($purchaseReturn->branch)
                            <h5 class="company_name">{{ $purchaseReturn->branch->name.'/'.$purchaseReturn->branch->branch_code}}</h5>
                            <p class="company_address">
                                {{ $purchaseReturn->branch->city }}, 
                                {{ $purchaseReturn->branch->state }}, 
                                {{ $purchaseReturn->branch->zip_code }},
                                {{ $purchaseReturn->branch->country }},
                            </p>
                            <p class="company_address">Phone : +88-0185226677</p>
                        @else
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p class="company_address">Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        @endif
                        
                        <h6 class="bill_name">Purchase Return Invoice</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="purchase_return_and_deal_info pt-3">
            <div class="row">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li><strong>Return Details : </strong> </li>
                        <li><strong>PR.Invoice ID : </strong> <span class="return_invoice_id">{{ $purchaseReturn->invoice_id }}</span></li>
                        <li><strong>Return Date : </strong> <span class="return_date">{{ $purchaseReturn->date }}</span></li>
                        <li><strong>Supplier Name : </strong> {{ $purchaseReturn->supplier->name }}</li>
                        <li><strong>Return Stock Loction : </strong> 
                            @if ($purchaseReturn->warehouse)
                                {{ $purchaseReturn->warehouse->warehouse_name.'/'.$purchaseReturn->warehouse->warehouse_code }}<b>(WAREHOUSE)</b>
                            @elseif($purchaseReturn->branch)
                                {{ $purchaseReturn->branch->name.'/'.$purchaseReturn->branch->branch_code }} <b>(BRANCH)</b>
                            @else 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(Head Office)</b> 
                            @endif
                        </li>
                    </ul>
                </div>
                
                <div class="col-6">
                    <ul class="list-unstyled float-right">
                        <li><strong>Purchase Details : </li>
                        <li><strong>Invoice No : </strong> {{ $purchaseReturn->purchase ? $purchaseReturn->purchase->invoice_id : 'N/A' }}</li>
                        <li><strong>Date : </strong>{{ $purchaseReturn->purchase ? $purchaseReturn->purchase->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Product</th>
                            <th scope="col">Unit Cost</th>
                            <th scope="col">Return Quantity</th>
                            <th scope="col">SubTotal</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="purchase_return_print_product_list">
                    @foreach ($purchaseReturn->purchase_return_products as $purchase_return_product)
                        @if ($purchase_return_product->return_qty > 0)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    {{ $purchase_return_product->purchase_product->product->name }}
                                    @if ($purchase_return_product->purchase_product->variant)
                                        -{{ $purchase_return_product->purchase_product->variant->variant_name }}
                                    @endif
                                    @if ($purchase_return_product->purchase_product->variant)
                                        ({{ $purchase_return_product->purchase_product->variant->variant_code }})
                                    @else   
                                    ({{ $purchase_return_product->purchase_product->product->product_code }}) 
                                    @endif
                                </td>
                                <td>
                                    {{ $purchase_return_product->purchase_product->net_unit_cost  }}
                                </td>
                                <td>
                                    {{ $purchase_return_product->return_qty }} ({{ $purchase_return_product->unit }})
                                </td>
                                <td>
                                    {{ $purchase_return_product->return_subtotal }} 
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Return Amount</th>
                        <td colspan="2" class="total_return_amount">{{ $purchaseReturn->total_return_amount }}</td>
                    </tr>

                    <tr>
                        <th colspan="4">Total Due</th>
                        <td colspan="2" class="total_due">{{ $purchaseReturn->purchase->purchase_return_due }}</td>
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

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchaseReturn->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$purchaseReturn->invoice_id}}</p>
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
