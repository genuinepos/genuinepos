<!-- Packing slip print templete-->
<style>
    .packing_slip_print_template{font-family: monospace!important;font-weight: bolder;}
    /* .sale_product_table table tbody tr td{font-size: 13px;} */
</style>
<div class="">
    <div>
        <div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class=" text-center">
                        <h1 >{{ json_decode($generalSettings->business, true)['shop_name'] }}</h1>
                        <h6><strong>{{$sale->branch ? $sale->branch->name . '/' . $sale->branch->branch_code : ''}}</strong> </h6>
                        @if ($sale->branch)
                            <strong>{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</strong><br>
                            <strong>Phone : {{ $sale->branch->phone }}</strong>
                        @else
                            <strong>{{ json_decode($generalSettings->business, true)['address'] }}</strong><br>
                            <strong>Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</strong>
                        @endif
                        <h6 >Packing Slip</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li>
                            <strong>Name : </strong>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                        </li>
                        <li>
                            <strong>Address : </strong>
                            @if ($sale->shipment_address)
                                {{  $sale->shipment_address }}
                            @else 
                                {{ $sale->customer ? $sale->customer->address : '' }}
                            @endif
                        </li>
                        <li>
                            <strong>Tax Number : </strong>{{ $sale->customer ? $sale->customer->tax_number : '' }}
                        </li>
                        <li>
                            <strong>Phone : </strong> {{ $sale->customer ? $sale->customer->phone : '' }}
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>INV ID : </strong> {{ $sale->invoice_id }}
                        </li>
                        <li><strong>Date : </strong>{{ $sale->date }}</li>
                    </ul>
                </div>
                {{-- <div class="col-lg-4">
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
                </div> --}}
            </div>
        </div>

        <div class="pt-3 pb-3">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="" class="text-start">SL</th>
                        <th class="text-start">Description</th>
                        <th class="text-start">Unit</th>
                        <th class="text-start">Quantity</th>
                    </tr>
                </thead>
                <thead>
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
                </thead>
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