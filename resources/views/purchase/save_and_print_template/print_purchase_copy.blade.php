@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 33px;
        margin-left: 20px;
        margin-right: 20px;
    }

    .header,
    .header-space,
    .footer,
    .footer-space {
        height: 20px;
    }

    .header {
        position: fixed;
        top: 0;
    }

    .footer {
        position: fixed;
        bottom: 0;
    }

    .noBorder {
        border: 0px !important;
    }

    tr.noBorder td {
        border: 0px !important;
    }

    tr.noBorder {
        border: 0px !important;
        border-left: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
</style>

<!-- Purchase print templete-->
<div class="purchase_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-4">
                    @if ($purchase->branch)
                        @if ($purchase->branch->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch->name }}</span>
                        @endif
                    @else
                        @if ($generalSettings['business__business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-4">
                    <div class="heading text-center">
                        <h4 class="bill_name">{{ __('PURCHASE INVOICE') }}</h4>
                    </div>
                </div>

                <div class="col-4">

                </div>
            </div>
        </div>

        <div class="purchase_and_deal_info pt-3">
            <div class="row">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.supplier') : - </strong></li>
                        <li><strong>@lang('menu.name') : </strong>{{ $purchase->supplier->name }}</li>
                        <li><strong>@lang('menu.address') : </strong>{{ $purchase->supplier->address }}</li>
                        <li><strong>@lang('menu.tax_number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                        <li><strong>@lang('menu.phone') : </strong> {{ $purchase->supplier->phone }}</li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.purchase_from') : </strong></li>
                        <li>
                            <strong>@lang('menu.business_location') : </strong>
                            @if ($purchase->branch)
                                {!! $purchase->branch->name . ' ' . $purchase->branch->branch_code . ' <b>(BL)</b>' !!}
                            @else
                                {{ $generalSettings['business__business_name'] }} (<b>HO</b>)
                            @endif
                        </li>

                        <li><strong>@lang('menu.stored_location') : </strong>
                            @if ($purchase->warehouse_id)
                                {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_code }}
                                (<b>WH</b>)
                            @elseif($purchase->branch_id)
                                {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                (<b>B.L</b>)
                            @else
                                {{ $generalSettings['business__business_name'] }} (<b>HO</b>)
                            @endif
                        </li>

                        <li><strong>@lang('menu.phone') : </strong>
                            @if ($purchase->branch)
                                {{ $purchase->branch->phone }}
                            @elseif($purchase->warehouse_id)
                                {{ $purchase->warehouse->phone }}
                            @else
                                {{ $generalSettings['business__phone'] }}
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled">
                        <li><strong>{{ __('P.Invoice ID') }} </strong> {{ $purchase->invoice_id }}</li>
                        <li><strong>@lang('menu.date') : </strong>{{ date($generalSettings['business__date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                        <li><strong>@lang('menu.purchases_status') : </strong>
                            <span class="purchase_status">
                                @if ($purchase->purchase_status == 1)
                                    {{ __('Purchased') }}
                                @elseif($purchase->purchase_status == 2)
                                    @lang('menu.pending')
                                @else
                                    @lang('menu.purchased_by_order')
                                @endif
                            </span>
                        </li>
                        <li><strong>@lang('menu.payment_status') : </strong>
                            @php
                                $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                            @endphp
                            @if ($purchase->due <= 0)
                                @lang('menu.paid')
                            @elseif($purchase->due > 0 && $purchase->due < $payable)
                                @lang('menu.partial')
                            @elseif($payable == $purchase->due)
                                @lang('menu.due')
                            @endif
                        </li>
                        <li><strong>@lang('menu.created_by') : </strong>
                            {{ $purchase->admin->prefix . ' ' . $purchase->admin->name . ' ' . $purchase->admin->last_name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>@lang('menu.description')</th>
                        <th>@lang('menu.quantity')</th>
                        <th>@lang('menu.unit_cost')({{ $generalSettings['business__currency_symbol'] }}) : </th>
                        <th scope="col">@lang('menu.unit_cost')({{ $generalSettings['business__currency_symbol'] }})</th>
                        <th scope="col">@lang('menu.tax')(%)</th>
                        <th scope="col">{{ __('Net Unit Cost') }}({{ $generalSettings['business__currency_symbol'] }})</th>
                        <th scope="col">@lang('menu.lot_number')</th>
                        <th scope="col">@lang('menu.subtotal')({{ $generalSettings['business__currency_symbol'] }})</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($purchase->purchase_products as $product)
                        <tr>
                            @php
                                $variant = $product->variant ? ' (' . $product->variant->variant_name . ')' : '';
                            @endphp

                            <td>
                                {{ Str::limit($product->product->name, 25) . ' ' . $variant }}
                                <small>{!! $product->description ? '<br/>' . $product->description : '' !!}</small>
                            </td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                            </td>
                            <td>{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                            <td>{{ $product->unit_tax . '(' . $product->unit_tax_percent . '%)' }}</td>
                            <td>{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }}</td>
                            <td>{{ $product->lot_no ? $product->lot_no : '' }}</td>
                            <td>{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.net_total_amount') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.purchase_discount') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.purchase_tax') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ $purchase->purchase_tax_amount . ' (' . $purchase->purchase_tax_percent . '%)' }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.shipment_charge') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">{{ __('Purchase Total') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.paid') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="7" class="text-end">@lang('menu.due') : {{ $generalSettings['business__currency_symbol'] }}</th>
                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <br>
        <div class="row">
            <div class="col-md-6">
                <h6>@lang('menu.checked_by')</h6>
            </div>

            <div class="col-md-6 text-end">
                <h6>@lang('menu.approved_by')</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{ $purchase->invoice_id }}</p>
            </div>
        </div>

        @if (env('PRINT_SD_PURCHASE') == true)
            <div class="row">
                <div class="col-md-12 text-center">
                    <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                </div>
            </div>
        @endif

        <div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
            <small style="font-size: 5px; float: right;" class="text-end">
                @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
            </small>
        </div>
    </div>
</div>
<!-- Purchase print templete end-->
