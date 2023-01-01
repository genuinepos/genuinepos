@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp
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
</style>
<!-- purchase print templete-->
<div class="purchase_return_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-4">
                    @if ($return->branch)
                        @if ($return->branch->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch->name }}</span>
                        @endif
                    @else
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-4">
                    <div class="heading text-center">
                        <h5 class="bill_name">@lang('menu.purchase_return_bill')</h5>
                    </div>
                </div>

                <div class="col-4">

                </div>
            </div>
        </div>

        <div class="purchase_return_and_deal_info pt-3">
            <div class="row">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.return_details') : </strong> </li>

                        <li><strong>{{ __('PR.Invoice ID') }} : </strong>
                            <span class="return_invoice_id">{{ $return->invoice_id }}</span>
                        </li>

                        <li><strong>@lang('menu.return_date') : </strong>
                            <span class="return_date">{{ $return->date }}</span>
                        </li>

                        <li><strong>@lang('menu.supplier_name') : </strong>
                            {{ $return->supplier ? $return->supplier->name : $return->purchase->supplier->name }}
                        </li>

                        <li><strong>{{ __('Return Stock Location') }} : </strong>
                            @if ($return->warehouse)

                                {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                            @elseif($return->branch)

                                {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(B.L)</b>
                            @else

                                {{ json_decode($generalSettings->business, true)['shop_name'] }}<b>(@lang('menu.head_office'))</b>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-6">
                    <ul class="list-unstyled float-right">
                        <li><strong>@lang('menu.purchase_details') : </strong> </li>
                        <li><strong>@lang('menu.invoice_no') : </strong> {{ $return->purchase ? $return->purchase->invoice_id : 'N/A' }}</li>
                        <li><strong>@lang('menu.date') : </strong>{{ $return->purchase ? $return->purchase->date : 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start">@lang('menu.sl')</th>
                            <th class="text-start">@lang('menu.product')</th>
                            <th class="text-end">@lang('menu.unit_cost')</th>
                            <th class="text-end">@lang('menu.return_quantity')</th>
                            <th class="text-end">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="purchase_return_print_product_list">
                    @foreach ($return->purchase_return_products as $purchase_return_product)
                        @if ($purchase_return_product->return_qty > 0)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>

                                <td class="text-start">
                                    {{ $purchase_return_product->product->name }}

                                    @if ($purchase_return_product->variant)

                                        -{{ $purchase_return_product->variant->variant_name }}
                                    @endif

                                    @if ($purchase_return_product->variant)

                                        ({{ $purchase_return_product->variant->variant_code }})
                                    @else

                                        ({{ $purchase_return_product->product->product_code }})
                                    @endif
                                </td>

                                <td class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase_return_product->unit_cost) }}
                                </td>

                                <td class="text-end">
                                    {{ $purchase_return_product->return_qty }} ({{ $purchase_return_product->unit }})
                                </td>

                                <td class="text-end">
                                    {{ $purchase_return_product->return_subtotal }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">@lang('menu.total_return_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                        <td colspan="2" class="text-end">{{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}</td>
                    </tr>

                    <tr>
                        <th colspan="4" class="text-end">@lang('menu.total_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>

                        <td colspan="2" class="text-end">

                            @if ($return->purchase_id)

                                {{ App\Utils\Converter::format_in_bdt($return->total_return_due) }}
                            @else
                            @lang('menu.check_supplier_due')
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br><br>

        <div class="note">
            <div class="row">
                <div class="col-md-6">
                    <h6><strong>@lang('menu.checked_by')</strong></h6>
                </div>
                <div class="col-md-6 text-end">
                    <h6><strong>@lang('menu.approved_by')</strong></h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$return->invoice_id}}</p>
            </div>
        </div>

        @if (env('PRINT_SD_PURCHASE') == true)
            <div class="row">
                <div class="col-md-12 text-center">
                    <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                </div>
            </div>
        @endif
    </div>
</div>
