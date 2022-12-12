@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    Transfer Details (@lang('menu.reference_id') : <strong>{{ $transfer->ref_id }}</strong>)
                </h6>

                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Send From : </strong></li>
                            <li><strong>B.Location Name :</strong>
                                {{ $transfer->sender_branch? $transfer->sender_branch->name . '/' . $transfer->sender_branch->branch_code: json_decode($generalSettings->business, true)['shop_name'] . '(HO)' }}
                            </li>
                            <li><strong>@lang('menu.phone') : </strong>
                                {{ $transfer->sender_branch? $transfer->sender_branch->phone: json_decode($generalSettings->business, true)['phone'] }}
                            </li>

                            @if ($transfer->sender_branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $transfer->sender_branch->city }},
                                    {{ $transfer->sender_branch->state }},
                                    {{ $transfer->sender_branch->zip_code }},
                                    {{ $transfer->sender_branch->country }}.
                                </li>
                            @else
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ json_decode($generalSettings->business, true)['address'] }}
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Receive From : </strong></li>
                            <li><strong>B.Location Name :</strong>
                                {{ $transfer->receiver_branch? $transfer->receiver_branch->name . '/' . $transfer->receiver_branch->branch_code: json_decode($generalSettings->business, true)['shop_name'] . '(HO)' }}
                            </li>
                            <li><strong>@lang('menu.phone') : </strong>
                                {{ $transfer->receiver_branch? $transfer->receiver_branch->phone: json_decode($generalSettings->business, true)['phone'] }}
                            </li>

                            @if ($transfer->receiver_branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $transfer->receiver_branch->city }},
                                    {{ $transfer->receiver_branch->state }},
                                    {{ $transfer->receiver_branch->zip_code }},
                                    {{ $transfer->receiver_branch->country }}.
                                </li>
                            @else
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ json_decode($generalSettings->business, true)['address'] }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled float-end">
                            <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                            <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->ref_id }}</li>
                            <li><strong>@lang('menu.status') : </strong>
                                @if ($transfer->receive_status == 1)

                                    <span class="text-danger">@lang('menu.pending')</span>
                                @elseif($transfer->receive_status == 2)

                                    <span class="text-primary">@lang('menu.partial')</span>
                                @elseif($transfer->receive_status == 3)
                                    <span class="text-success">@lang('menu.completed')</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th class="text-start">@lang('menu.sl')</th>
                                    <th class="text-start">@lang('menu.product')</th>
                                    <th class="text-start">@lang('menu.unit_cost_inc_tax')</th>
                                    <th class="text-start">@lang('menu.quantity')</th>
                                    <th class="text-start">@lang('menu.receive_qty')</th>
                                    <th class="text-start">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>

                            <tbody class="transfer_print_product_list">
                                @foreach ($transfer->transfer_products as $transfer_product)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                                        @endphp
                                        <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                                        <td class="text-start">{{ $transfer_product->unit_cost_inc_tax }}</td>
                                        <td class="text-start">{{ $transfer_product->send_qty.'/'.$transfer_product->product->unit->name }}</td>
                                        <td class="text-start">{{ $transfer_product->received_qty.'/'.$transfer_product->product->unit->name }}</td>
                                        <td class="text-start">{{ $transfer_product->subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end">Total Stock Value :</th>
                                    <td class="text-start">{{ $transfer->total_stock_value }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div> <br>
                <hr class="p-0 m-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <h6>Transfer Note : </h6>
                            <p>{{ $transfer->transfer_note }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="details_area">
                            <h6>Receiver Note : </h6>
                            <p>{{ $transfer->receiver_note }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">@lang('menu.close')</button>
                <button type="button" class="footer_btn btn btn-sm btn-success print_btn">@lang('menu.print')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<div class="transfer_print_template d-hide">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h6 class="bill_name">Transfer Stock Details (Business Location To Business Location)</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.from') : </strong></li>
                        <li><strong>B.Location Name :</strong> {{ $transfer->sender_branch ? $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ $transfer->sender_branch ? $transfer->sender_branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>

                        @if ($transfer->sender_branch)
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $transfer->sender_branch->city }},
                                {{ $transfer->sender_branch->state }},
                                {{ $transfer->sender_branch->zip_code }},
                                {{ $transfer->sender_branch->country }}.
                            </li>
                        @else
                            <li><strong>@lang('menu.address') : </strong>
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.to') : </strong></li>
                        <li><strong>B.Location Name :</strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->name.'/'.$transfer->receiver_branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->phone : json_decode($generalSettings->business, true)['phone'] }}</li>

                        @if ($transfer->receiver_branch)
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $transfer->receiver_branch->city }},
                                {{ $transfer->receiver_branch->state }},
                                {{ $transfer->receiver_branch->zip_code }},
                                {{ $transfer->receiver_branch->country }}.
                            </li>
                        @else
                            <li><strong>@lang('menu.address') : </strong>
                                {{ json_decode($generalSettings->business, true)['address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->ref_id }}</li>
                        <li><strong>@lang('menu.status') : </strong>
                            @if ($transfer->receive_status == 1)

                                <span class="text-danger">@lang('menu.pending')</span>
                            @elseif($transfer->receive_status == 2)

                                <span class="text-primary">@lang('menu.partial')</span>
                            @elseif($transfer->receive_status == 3)

                                <span class="text-success">@lang('menu.completed')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start">@lang('menu.sl')</th>
                            <th class="text-start">@lang('menu.product')</th>
                            <th class="text-start">@lang('menu.unit_cost_inc_tax')</th>
                            <th class="text-start">@lang('menu.quantity')</th>
                            <th class="text-start">@lang('menu.receive_qty')</th>
                            <th class="text-start">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="transfer_print_product_list">
                    @foreach ($transfer->transfer_products as $transfer_product)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                            @endphp
                            <td class="text-start">{{ $transfer_product->product->name.$variant }}</td>
                            <td class="text-start">{{ $transfer_product->unit_cost_inc_tax }}</td>
                            <td class="text-start">{{ $transfer_product->send_qty.'/'.$transfer_product->product->unit->name }}</td>
                            <td class="text-start">{{ $transfer_product->received_qty.'/'.$transfer_product->product->unit->name }}</td>
                            <td class="text-start">{{ $transfer_product->subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-end" colspan="5"><strong>Total Stock Value :</strong></td>
                        <td class="text-start">{{ $transfer->total_stock_value }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('menu.checked_by')</strong></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>@lang('menu.approved_by')</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->ref_id, $generator::TYPE_CODE_128)) }}">

                <p class="p-0 m-0">{{ $transfer->ref_id }}</b></small>

                @if (env('PRINT_SD_OTHERS') == true)
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
