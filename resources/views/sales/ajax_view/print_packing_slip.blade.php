<!-- Packing slip print templete-->
<div class="packing_slip_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                        <p class="company_name">{{$sale->branch ? $sale->branch->name . '/' . $sale->branch->branch_code : ''}}</p>
                        @if ($sale->branch)
                            <small class="company_address">{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</small><br>
                            <small class="company_address">Phone : {{ $sale->branch->phone }}</small>
                        @else
                            <small class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</small><br>
                            <small class="company_address">Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</small>
                        @endif
                        <h6 class="bill_name">Packing Slip</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>Namne : </strong>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</li>
                        <li><strong>Address : </strong> {{ $sale->customer ? $sale->customer->address : '' }}</li>
                        <li><strong>Tax Number : </strong>{{ $sale->customer ? $sale->customer->tax_number : '' }}</li>
                        <li><strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>Invoice ID : </strong> {{ $sale->invoice_id }}
                        </li>
                        <li><strong>Date : </strong>{{ $sale->date }}</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled float-right">
                        <li><strong>Shipping Address : </strong></li>
                        <li>
                            @if ($sale->shipment_address)
                                {{ $sale->shipment_address }}
                            @else
                                @if ($sale->customer)
                                    {{ $sale->customer->shipping_address }}
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-start">SL</th>
                        <th class="text-start">Description</th>
                        <th class="text-start">Unit</th>
                        <th class="text-start">Quantity</th>
                    </tr>
                </thead>
                <tbody class="packing_product_list">
                    @foreach ($sale->sale_products as $saleProduct)
                        <tr>
                            @php
                                $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                            @endphp
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            <td class="text-start"><p><b> {{ $saleProduct->product->name . $variant }}</b></p> </td>
                            <td class="text-start">{{ $saleProduct->unit }}
                            <td class="text-start">{{ $saleProduct->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><br>
        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>Authorized Signature</strong></h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Packing slip print templete end-->