@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<div class="transfer_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h6 class="bill_name">{{ __('Transfer Stock Details (Business Location To Business Location)') }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.from') : </strong></li>
                        <li><strong>{{ __('B.Location Name') }} </strong> {{ $transfer->sender_branch ? $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code : $generalSettings['business__shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ $transfer->sender_branch ? $transfer->sender_branch->phone : $generalSettings['business__phone'] }}</li>

                        <li><strong>@lang('menu.stock_location') : </strong>
                            @if ($transfer->sender_warehouse)

                                {{ $transfer->sender_warehouse->warehouse_name.'/'.$transfer->sender_warehouse->warehouse_code.'(WH)' }}
                            @else

                                {{ $transfer->sender_branch ? $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code.'(B.L)' : $generalSettings['business__shop_name'].'(HO)' }}
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.to') : </strong></li>
                        <li><strong>{{ __('B.Location Name') }} </strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->name.'/'.$transfer->receiver_branch->branch_code : $generalSettings['business__shop_name'].'(HO)' }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ $transfer->receiver_branch ? $transfer->receiver_branch->phone : $generalSettings['business__phone'] }}</li>

                        @if ($transfer->receiver_branch)
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $transfer->receiver_branch->city }},
                                {{ $transfer->receiver_branch->state }},
                                {{ $transfer->receiver_branch->zip_code }},
                                {{ $transfer->receiver_branch->country }}.
                            </li>
                        @else
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $generalSettings['business__address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-4">
                    <ul class="list-unstyled float-end">
                        <li><strong>@lang('menu.date') : </strong> {{ $transfer->date }}</li>
                        <li><strong>@lang('menu.reference_id') : </strong> {{ $transfer->ref_id }}</li>
                        <li><strong>@lang('menu.status') : </strong>
                            @lang('menu.pending')
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
                        <td class="text-end" colspan="5"><strong>@lang('menu.total_stock_value') : </strong></td>
                        <td class="text-start">{{ $transfer->total_stock_value }}</td>
                    </tr>

                    <tr>
                        <th class="text-end" colspan="5">@lang('menu.transfer_cost')</th>
                        <td class="text-start">{{ $transfer->transfer_cost }}</td>
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