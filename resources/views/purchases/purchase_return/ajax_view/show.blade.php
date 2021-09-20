@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp 
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog col-60-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Purchase return (Purchase Return Invoice ID : <strong>{{ $return->invoice_id }}</strong>)
                </h5>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>
                                <strong>Return Details : </strong> </li>
                            <li>
                                <strong>PR.Invoice ID : </strong> {{ $return->invoice_id }}
                            </li>
                            <li>
                                <strong>Return Date : </strong> {{ $return->date }}
                            </li>
                            <li>
                                <strong>supplier Name : </strong> 
                                {{ $return->purchase ? $return->purchase->supplier->name.' (ID'.$return->purchase->supplier->contact_id.')' : $return->supplier->name.' (ID'.$return->supplier->contact_id.')' }}</span>
                            </li>
                            <li class="warehouse"><strong>Business Location : </strong> 
                                @if($return->branch) 
                                    {{ $return->branch->name.'/'.$return->branch->branch_code }}<b>(BL)</b>
                                @else 
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(HO)</b>
                                @endif
                            </li>
                            <li class="warehouse"><strong>Return Stock Location : </strong> 
                                @if ($return->warehouse)
                                    {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                                @elseif($return->branch)
                                    {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(BL)</b>
                                @else 
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(HO)</b> 
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-1 text-left">
                        <ul class="list-unstyled">

                        </ul>
                    </div>
                    <div class="col-md-5 text-left">
                        <ul class="list-unstyled">
                            <li class="parent_purchase"><strong>Purchase Details :</strong>  </li>
                            <li class="parent_purchase">
                                <strong>P.Invoice ID : </strong> 
                                {{ $return->purchase ? $return->purchase->invoice_id : 'N/A' }}
                            </li>
                            <li class="parent_purchase"><strong>Date : </strong> 
                                {{ $return->purchase ? $return->purchase->date : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white text-start">
                                    <th class="text-start" scope="col">SL</th>
                                    <th class="text-start" scope="col">Product</th>
                                    <th class="text-start" scope="col">Unit cost</th>
                                    <th class="text-start" scope="col">Return Quantity</th>
                                    <th class="text-start" scope="col">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody class="purchase_return_product_list">
                                @foreach ($return->purchase_return_products as $return_product)
                                    @if ($return_product->return_qty > 0)
                                        <tr>
                                            <td class="text-start">{{ $loop->index + 1 }}</td>
                                            <td class="text-start">
                                                {{ $return_product->product->name }}
                                                @if ($return_product->variant)
                                                    -{{ $return_product->variant->variant_name }}
                                                @endif
                                                @if ($return_product->variant)
                                                    ({{ $return_product->variant->variant_code }})
                                                @else   
                                                ({{ $return_product->product->product_code }}) 
                                                @endif
                                            </td>

                                            <td class="text-start">
                                                @if ($return_product->purchase_product)
                                                    {{ $return_product->purchase_product->net_unit_cost }}
                                                @else
                                                    @if ($return_product->variant)
                                                        {{ $return_product->variant->variant_cost_with_tax }}
                                                    @else
                                                        {{ $return_product->product->product_cost_with_tax }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start">
                                                {{ $return_product->return_qty }} ({{ $return_product->unit }})
                                            </td>

                                            <td class="text-start">
                                                {{ $return_product->return_subtotal }} 
                                            </td>
                                        </tr> 
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th class="text-start">Total Return Amount : </th>
                                    <td class="total_return_amount text-start">{{ $return->total_return_amount }}</td>
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
<div class="purchase_return_print_template d-none">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        @if ($return->branch)
                            <h5 class="company_name">
                                {{ $return->branch->name.'/'.$return->branch->branch_code}}
                            </h5>
                            <p class="company_address">
                                {{ $return->branch->city }}, 
                                {{ $return->branch->state }}, 
                                {{ $return->branch->zip_code }},
                                {{ $return->branch->country }},
                            </p>
                            <p class="company_address">Phone : +88-0185226677</p>
                        @else
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}(Head Office)</h5>
                            <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                            <p class="company_address">Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                        @endif
                        <h6 class="bill_name">Purchase Return Details</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>Return Details : </strong> </li>
                        <li><strong>PR.Invoice ID : </strong> <span class="return_invoice_id">{{ $return->invoice_id }}</span></li>
                        <li><strong>Return Date : </strong> <span class="return_date">{{ $return->date }}</span></li>
                        <li><strong>Supplier Name : </strong> 
                            {{ $return->purchase ? $return->purchase->supplier->name.' (ID'.$return->purchase->supplier->contact_id.')' : $return->supplier->name.' (ID'.$return->supplier->contact_id.')' }}</span>
                        </li>
                        <li><strong>Return Stock Loction : </strong> 
                            @if ($return->warehouse)
                                {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WAREHOUSE)</b>
                            @elseif($return->branch)
                                {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(BL)</b>
                            @else 
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}(HO)
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">

                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>Purchase Details : </strong> </li>
                        <li><strong>P.Invoice No : </strong> {{ $return->purchase ? $return->purchase->invoice_id : 'N/A' }}</li>
                        <li><strong>Date : </strong>{{ $return->purchase ? $return->purchase->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                    <tr>
                        <th class="text-start" scope="col">SL</th>
                        <th class="text-start" scope="col">Product</th>
                        <th class="text-start" scope="col">Unit Price</th>
                        <th class="text-start" scope="col">Return Quantity</th>
                        <th class="text-start" scope="col">SubTotal</th>
                    </tr>
                    </tr>
                </thead>
                <tbody class="purchase_return_print_product_list">
                    @foreach ($return->purchase_return_products as $return_product)
                        @if ($return_product->return_qty > 0)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $return_product->product->name }}
                                    @if ($return_product->variant)
                                        -{{ $return_product->variant->variant_name }}
                                    @endif
                                    @if ($return_product->variant)
                                        ({{ $return_product->variant->variant_code }})
                                    @else   
                                    ({{ $return_product->product->product_code }}) 
                                    @endif
                                </td>

                                <td class="text-start">
                                    @if ($return_product->purchase_product)
                                        {{ $return_product->purchase_product->net_unit_cost }}
                                    @else
                                        @if ($return_product->variant)
                                            {{ $return_product->variant->variant_cost_with_tax }}
                                        @else
                                            {{ $return_product->product->product_cost_with_tax }}
                                        @endif
                                    @endif
                                </td>

                                <td class="text-start">
                                    {{ $return_product->return_qty }} ({{ $return_product->unit }})
                                </td>

                                <td class="text-start">
                                    {{ $return_product->return_subtotal }} 
                                </td>
                            </tr> 
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-start" colspan="4">Total Return</th>
                        <td class="text-start" colspan="2" class="total_return_amount">10000.00</td>
                    </tr>

                    <tr>
                        <th class="text-start" colspan="4">Total Due</th>
                        <td class="text-start" colspan="2" class="total_due">10000.00</td>
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
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$return->invoice_id}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <small>Software by <b>SpeedDigit Pvt. Ltd.</b></small>
            </div>
        </div>
    </div>
</div>
<!-- Sale print templete end-->
