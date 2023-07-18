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
                    @if ($purchase->branch->logo != 'default.png')

                        <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $order->branch->logo) }}">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $order->branch->name }}</span>
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
                <p style="text-transform: uppercase;">
                    <strong>
                        @if ($order->branch)

                            {!! $order->branch->name.' '.$order->branch->branch_code.' <b>(BL)</b>' !!}
                        @else

                            {{ $generalSettings['business__shop_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($order?->branch)

                        {{  $order->branch->city . ', ' . $order->branch->state. ', ' . $order->branch->zip_code. ', ' . $order->branch->country }},
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
                <h4 style="text-transform: uppercase;"><strong>@lang('menu.purchase_order')</strong></h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong><b>{{ $order->supplier->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong><b>{{ $order->supplier->address }}</b></li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong><b>{{ $order->supplier->phone }}</b></li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.po_id') : </strong> {{ $order->invoice_id }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __('P/o Date') }} : </strong>{{ date($generalSettings['business__date_format'], strtotime($order->date)) . ' ' . date($timeFormat, strtotime($order->time)) }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        {{ $order->admin->prefix.' '.$order->admin->name.' '.$order->admin->last_name }}
                    </li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">

                    <li style="font-size:11px!important;"><strong>@lang('menu.delivery_date') : </strong>{{ $order->delivery_date ? date($generalSettings['business__date_format'], strtotime($order->delivery_date)) : '' }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.receiving_status') : </strong>{{ $order->po_receiving_status }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.payment_status') : </strong>
                        @php
                            $payable = $order->total_purchase_amount - $order->total_return_amount;
                        @endphp
                        @if ($order->due <= 0)
                            @lang('menu.paid')
                        @elseif($order->due > 0 && $order->due < $payable)
                            @lang('menu.partial')
                        @elseif($payable == $order->due)
                            @lang('menu.due')
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.ordered_quantity')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.discount')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.subtotal')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.pending_qty')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.received_qty')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($order->purchase_order_products as $product)
                        <tr>
                            @php
                                $variant = $product->variant ? ' (' . $product->variant->variant_name . ')' : '';
                            @endphp

                            <td class="text-start" style="font-size:11px!important;">
                                {{ Str::limit($product->product->name, 25) . ' ' . $variant }}
                                <small>{!! $product->description ? '<br/>' . $product->description : '' !!}</small>
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $product->order_quantity }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ $product->pending_quantity }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ $product->received_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6">
                <p style="font-size:11px!important;"><strong>@lang('menu.order_note') : </strong> </p>
                <p style="font-size:11px!important;">{{ $order->purchase_note }}</p><br>
                <p style="font-size:11px!important;"><strong>@lang('menu.shipment_details') : </strong> </p>
                <p style="font-size:11px!important;">{{ $order->shipment_details }}</p>
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.order_discount') :
                                {{ $generalSettings['business__currency'] }}
                            </th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->order_discount) }} {{$order->order_discount_type == 1 ? '(Fixed)' : '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.order_tax') : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->purchase_tax_amount).' ('.$order->purchase_tax_percent.'%)' }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">{{ __('Order Total') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.paid') : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="11" class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.due') : {{ $generalSettings['business__currency'] }}</th>
                            <td colspan="2" class="text-end fw-bold" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($order->due) }}
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
                    <small style="font-size: 9px!important;">@lang('menu.print_date') : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->
