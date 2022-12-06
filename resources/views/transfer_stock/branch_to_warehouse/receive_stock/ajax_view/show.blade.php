    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-full-display">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                  Send Stock Details
              </h5>
              <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>Warehouse (From) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->branch ? $sendStock->branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else
                                <li><strong>@lang('menu.address') : </strong> {{ json_decode($generalSettings->business, true)['address'] }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>Reference ID : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>Status : </strong>
                                @if ($sendStock->status == 1)
                                <span class="badge bg-danger">Pending</span>
                                @elseif($sendStock->status == 2)
                                    <span class="badge bg-primary">Partial</span>
                                @elseif($sendStock->status == 3)
                                <span class="badge bg-success">Completed</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table table-sm modal-table">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th class="text-start">@lang('menu.sl')</th>
                                    <th class="text-start">@lang('menu.product')</th>
                                    <th class="text-start">Unit Price</th>
                                    <th class="text-start">@lang('menu.quantity')</th>
                                    <th class="text-start">Unit</th>
                                    <th class="text-start">Pending Qty</th>
                                    <th class="text-start">Received Qty</th>
                                    <th class="text-start">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody class="transfer_print_product_list">
                                @foreach ($sendStock->transfer_products as $transfer_product)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        @endphp
                                        <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                        <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                        <td class="text-start">{{ $transfer_product->quantity }}</td>
                                        <td class="text-start">{{ $transfer_product->unit }}</td>
                                        @php
                                            $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                        @endphp
                                        <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                        <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                        <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

              <hr class="p-0 m-0">
              <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>Receiver Note : </h6>
                        <p class="receiver_note">{{ $sendStock->receiver_note }}</p>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
            </div>
          </div>
        </div>
    </div>
    <!-- Details Modal End-->

    <!-- Transfer print templete-->
    <div class="transfer_print_template d-hide">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="heading text-center">
                            <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                            <small class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</small><br>
                            <small class="company_address">Phone : {{ json_decode($generalSettings->business, true)['phone'] }}</small>
                            <h6 class="bill_name">Send Stock Invoice</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Warehouse (Form) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->branch ? $sendStock->branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else
                            <li>
                                <strong>@lang('menu.address') : </strong> {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <<li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>Reference ID : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>Status : </strong>
                                @if ($sendStock->status == 1)
                                    Pending
                                @elseif($sendStock->status == 2)
                                    Partial
                                @elseif($sendStock->status == 3)
                                    Complated
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sale_product_table pt-3 pb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <tr>
                                <th scope="col">@lang('menu.sl')</th>
                                <th scope="col">@lang('menu.product')</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">@lang('menu.quantity')</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Pending Qty</th>
                                <th scope="col">Received Qty</th>
                                <th scope="col">SubTotal</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="transfer_print_product_list">
                        @foreach ($sendStock->transfer_products as $transfer_product)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                @php
                                    $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                @endphp
                                <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                <td class="text-start">{{ $transfer_product->unit_price}}</td>
                                <td class="text-start">{{ $transfer_product->quantity }}</td>
                                <td class="text-start">{{ $transfer_product->unit }}</td>
                                @php
                                    $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
                                @endphp
                                <td class="text-start"><b>{{ $panding_qty.' ('.$transfer_product->unit.')' }}</b></td>
                                <td class="text-start">{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</td>
                                <td class="text-start">{{ $transfer_product->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br>
            <div class="note">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Receiver's Signature</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>@lang('menu.signature_of_authority')</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer print templete end-->
