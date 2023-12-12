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

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
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

                            {{ $generalSettings['business__business_name'] }}
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

                        <strong>@lang('menu.email') : </strong> <b>{{ $order?->branch?->email }}</b>,
                        <strong>@lang('menu.phone') : </strong> <b>{{ $order?->branch?->phone }}</b>
                    @else

                        <strong>@lang('menu.email') : </strong> <b>{{ $generalSettings['business__email'] }}</b>,
                        <strong>@lang('menu.phone') : </strong> <b>{{ $generalSettings['business__phone'] }}</b>
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
                    <li style="font-size:11px!important;"><strong>{{ __("Supplier") }} : </strong><b>{{ $order?->supplier?->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong><b>{{ $order?->supplier?->address }}</b></li>
                    <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong><b>{{ $order?->supplier?->phone }}</b></li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("P/o ID") }} : </strong> {{ $order->invoice_id }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('P/o Date') }} : </strong>{{ date($generalSettings['business__date_format'], strtotime($order->date)) . ' ' . date($timeFormat, strtotime($order->time)) }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        {{ $order?->admin?->prefix.' '.$order?->admin?->name.' '.$order?->admin?->last_name }}
                    </li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">

                    <li style="font-size:11px!important;"><strong>{{ __("Delivery Date") }} : </strong>{{ $order->delivery_date ? date($generalSettings['business__date_format'], strtotime($order->delivery_date)) : '' }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Receiving Status") }} : </strong>{{ $order->po_receiving_status }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Payment Status") }} : </strong>
                        @php
                            $payable = $order->total_purchase_amount - $order->total_return_amount;
                        @endphp
                        @if ($order->due <= 0)

                            {{ __("Paid") }}
                        @elseif($order->due > 0 && $order->due < $payable)

                            {{ __("Partial") }}
                        @elseif($payable == $order->due)

                            {{ __("Due") }}
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table print-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Description") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Ordered Qty") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost(Exc. Tax)") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Discount") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost(Inc. Tax)") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Pending Qty") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Received Qty") }}</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($order->purchaseOrderProducts as $orderProduct)
                        <tr>
                            @php
                                $variant = $orderProduct?->variant ? ' - ' . $orderProduct?->variant?->variant_name : '';
                            @endphp

                            <td class="text-start" style="font-size:11px!important;">
                                {{ Str::limit($orderProduct->product->name, 25) . ' ' . $variant }}
                                <small>{!! $orderProduct->description ? '<br/>' . $orderProduct->description : '' !!}</small>
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->ordered_quantity }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($orderProduct->unit_cost_exc_tax) }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->unit_discount) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ '('.$orderProduct->unit_tax_percent.'%)='.$orderProduct->unit_tax_amount }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->net_unit_cost) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($orderProduct->line_total) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->pending_quantity }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ $orderProduct->received_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6">
                <p style="font-size:11px!important;"><strong>{{ __("Order Note") }} : </strong> </p>
                <p style="font-size:11px!important;">{{ $order->purchase_note }}</p><br>
                <p style="font-size:11px!important;"><strong>{{ __("Shipment Details") }} : </strong> </p>
                <p style="font-size:11px!important;">{{ $order->shipment_details }}</p>
            </div>

            <div class="col-6">
                <table class="table print-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Order Discount") }} :
                                {{ $generalSettings['business__currency'] }}
                            </th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->order_discount) }} {{$order->order_discount_type == 1 ? '(Fixed)' : '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Order Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ '('.$order->purchase_tax_percent.'%)=' . App\Utils\Converter::format_in_bdt($order->purchase_tax_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Ordered Amount') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($payingAmount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Due (On Order)") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->due) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Current Balance") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt(0) }}</b>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
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
