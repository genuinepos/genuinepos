@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 20px;margin-right: 20px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
    <!-- Purchase Order print templete-->
    <div class="purchase_print_template">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
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
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <h4 class="bill_name">{{ __('Purchase Order Bill') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">

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
                            <li><strong>@lang('menu.ordered_from') : </strong></li>
                            <li>
                                <strong>@lang('menu.business_location') : </strong>
                                @if ($purchase->branch)
                                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                                @else
                                    {{ $generalSettings['business__shop_name'] }} (<b>@lang('menu.head_office')</b>)
                                @endif
                            </li>
                            <li><strong>{{ __('Ordered Location') }} </strong>
                                @if($purchase->branch_id)
                                    {{ $purchase->branch->city }}, {{ $purchase->branch->state }},
                                    {{ $purchase->branch->zip_code }}, {{ $purchase->branch->country }}
                                @else
                                    {{ $generalSettings['business__address'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.po_invoice_id') : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('menu.purchase_date') : </strong>
                                {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}
                            </li>

                            <li><strong>@lang('menu.delivery_date') : </strong>
                                {{ $purchase->delivery_date ? date($generalSettings['business__date_format'], strtotime($purchase->delivery_date)) : '' }}
                            </li>

                            <li><strong>@lang('menu.purchases_status') : </strong>@lang('menu.ordered')</li>
                            <li><strong>@lang('menu.created_by') : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('menu.sl')</th>
                            <th class="text-start">@lang('menu.description')</th>
                            <th scope="col">@lang('menu.ordered_quantity')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @php $index = 0; @endphp
                        @foreach ($purchase->purchase_order_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                                @endphp
                                <td class="text-start">{{ $index + 1 }}</td>
                                <td class="text-start">
                                    {{ Str::limit($product->product->name, 25).' '.$variant }}
                                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                                </td>
                                <td>{{ $product->order_quantity }}</td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>@lang('menu.perceived_by') : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>@lang('menu.authorized_by') : </h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                    <p>{{$purchase->invoice_id}}</p>
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