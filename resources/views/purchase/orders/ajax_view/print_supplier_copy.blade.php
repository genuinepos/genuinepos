@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
    <!-- Purchase Order print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($order->branch)

                        @if ($order?->branch?->parent_branch_id)

                            @if ($order->branch?->parentBranch?->logo != 'default.png')

                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $order->branch?->parentBranch?->logo) }}">
                            @else

                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $order->branch?->parentBranch?->name }}</span>
                            @endif
                        @else

                            @if ($order->branch?->logo != 'default.png')

                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $order->branch?->logo) }}">
                            @else

                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $order->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business__business_logo'] != null)

                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0">
                        <strong>
                            @if ($order?->branch)
                                @if ($order?->branch?->parent_branch_id)

                                    {{ $order?->branch?->parentBranch?->name }}
                                @else

                                    {{ $order?->branch?->name }}
                                @endif
                            @else

                                {{ $generalSettings['business__shop_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p>
                        @if ($order?->branch)

                            {{ $order->branch->city . ', ' . $order->branch->state. ', ' . $order->branch->zip_code. ', ' . $order->branch->country }}
                        @else

                            {{ $generalSettings['business__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($order?->branch)

                            <strong>@lang('menu.email') : </strong>{{ $order?->branch?->email }},
                            <strong>@lang('menu.phone') : </strong>{{ $order?->branch?->phone }}
                        @else

                            <strong>@lang('menu.email') : </strong>{{ $generalSettings['business__email'] }},
                            <strong>@lang('menu.phone') : </strong>{{ $generalSettings['business__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h4 style="text-transform: uppercase;"><strong>{{ __("Purchase Order") }}</strong></h4>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>{{ __("Supplier") }} : </strong>{{ $order?->supplier?->name }}</li>
                        <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong>{{ $order?->supplier?->address }}</li>
                        <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>{{ $order?->supplier?->phone }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>{{ __("P/o ID") }} : </strong> {{ $order->invoice_id }}</li>
                        <li style="font-size:11px!important;"><strong>{{ __('P/o Date') }} : </strong>{{ date($generalSettings['business__date_format'], strtotime($order->date))}}</li>
                        <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                            {{ $order?->admin?->prefix.' '.$order?->admin?->name.' '.$order?->admin?->last_name }}
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>{{ __("Delivery Date") }} : </strong>{{ $order->delivery_date ? date($generalSettings['business__date_format'], strtotime($order->delivery_date)) : '' }}</li>
                        <li style="font-size:11px!important;"><strong>{{ __("Receiving Status") }} : </strong>{{ $order->po_receiving_status }}</li>
                    </ul>
                </div>
            </div>

            <div class="purchase_product_table mt-2">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("S/L") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Description") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Ordered Quantity") }}</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php $index = 0; @endphp
                        @foreach ($order->purchaseOrderProducts as $orderProduct)
                            <tr>
                                @php
                                    $variant = $orderProduct?->variant ? ' ('.$orderProduct?->variant?->variant_name.')' : '';
                                @endphp
                                <td class="text-start" style="font-size:11px!important;">{{ $index + 1 }}</td>
                                <td class="text-start" style="font-size:11px!important;">
                                    {{ Str::limit($orderProduct->product->name, 25).' '.$variant }}
                                    <small>{!! $orderProduct->description ? '<br/>'.$orderProduct->description : '' !!}</small>
                                </td>
                                <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->ordered_quantity }}</td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br/><br/>
            <div class="row">
                <div class="col-4 text-start">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">{{ __("Prepared By") }}</p>
                </div>

                <div class="col-4 text-center">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">Checked By</p>
                </div>

                <div class="col-4 text-end">
                    <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">Authorized By</p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($order->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{ $order->invoice_id }}</p>
                </div>
            </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-start">
                        <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_company'))
                            <small class="d-block" style="font-size: 9px!important;">{{ __("Powered By") }} <strong>{{ __("Speed Digit Software Solution") }}.</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-end">
                        <small style="font-size: 9px!important;">{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!-- Purchase print templete end-->
