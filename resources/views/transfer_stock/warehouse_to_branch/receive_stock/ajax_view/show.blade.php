    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-full-display">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">@lang('menu.send_stock_details') </h5>
              <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : $generalSettings['business__shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->branch ? $sendStock->branch->phone : $generalSettings['business__phone'] }}</li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else
                                {{ $generalSettings['business__address'] }}
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.warehouse') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('menu.reference_id') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('menu.status') : </strong>
                                @if ($sendStock->status == 1)
                                <span class="badge bg-danger">@lang('menu.pending')</span>
                                @elseif($sendStock->status == 2)
                                    <span class="badge bg-primary">@lang('menu.partial')</span>
                                @elseif($sendStock->status == 3)
                                <span class="badge bg-success">@lang('menu.completed')</span>
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
                                    <th class="text-start">@lang('menu.unit_price')</th>
                                    <th class="text-start">@lang('menu.quantity')</th>
                                    <th class="text-start">@lang('menu.unit')</th>
                                    <th class="text-start">@lang('menu.pending_qty')</th>
                                    <th class="text-start">@lang('menu.received_qty')</th>
                                    <th class="text-start">@lang('menu.sub_total')</th>
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
                        <h6>{{ __('Receiver Note') }} : </h6>
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
                            <h5 class="company_name">{{ $generalSettings['business__shop_name'] }}</h5>
                            <p class="company_address">{{ $generalSettings['business__address'] }}</p>
                            <p class="company_address">Phone : {{ $generalSettings['business__phone'] }}</p>
                            <h6 class="bill_name">@lang('menu.send_stock_details')</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.b_location') (From) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->branch ? $sendStock->branch->name.'/'.$sendStock->branch->branch_code : $generalSettings['business__shop_name'].'(HO)' }}</li>
                            <li><strong>@lang('menu.phone') : </strong>
                                {{ $sendStock->branch ? $sendStock->branch->phone : $generalSettings['business__phone'] }}
                            </li>
                            @if ($sendStock->branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $sendStock->branch->city }},
                                    {{ $sendStock->branch->state }},
                                    {{ $sendStock->branch->zip_code }},
                                    {{ $sendStock->branch->country }}.
                                </li>
                            @else
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $generalSettings['business__address'] }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.warehouse') (To) : </strong></li>
                            <li><strong>@lang('menu.name') :</strong> {{ $sendStock->warehouse->warehouse_name.'/'.$sendStock->warehouse->warehouse_code }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $sendStock->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $sendStock->warehouse->address }}</li>
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <li><strong>@lang('menu.date') : </strong> {{ $sendStock->date }}</li>
                            <li><strong>@lang('menu.reference_id') : </strong>{{ $sendStock->invoice_id }}</li>
                            <li><strong>@lang('menu.status') : </strong>
                                @if ($sendStock->status == 1)
                                @lang('menu.pending')
                                @elseif($sendStock->status == 2)
                                @lang('menu.partial')
                                @elseif($sendStock->status == 3)
                                @lang('menu.completed')
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
                                <th scope="col">@lang('menu.unit_price')</th>
                                <th scope="col">@lang('menu.quantity')</th>
                                <th scope="col">@lang('menu.unit')</th>
                                <th scope="col">@lang('menu.pending_qty')</th>
                                <th scope="col">@lang('menu.received_qty')</th>
                                <th scope="col">@lang('menu.sub_total')</th>
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
                        <h6><strong>{{ __('Receivers signature') }}</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>@lang('menu.signature_of_authority')</strong></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer print templete end-->
