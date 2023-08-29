@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.purchase_return') ({{ __('Purchase Return Invoice ID') }} : <strong>{{ $return->invoice_id }}</strong>)
                </h5>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('menu.return_details') : </strong> </li>
                            <li>
                                <strong>{{ __('PR.Invoice ID') }} </strong> {{ $return->invoice_id }}
                            </li>
                            <li>
                                <strong>@lang('menu.return_date') : </strong> {{ $return->date }}
                            </li>
                            <li>
                                <strong>@lang('menu.supplier_name') : </strong>
                                {{ $return->purchase ? $return->purchase->supplier->name.' (ID'.$return->purchase->supplier->contact_id.')' : $return->supplier->name.' (ID'.$return->supplier->contact_id.')' }}</span>
                            </li>
                            <li class="warehouse"><strong>@lang('menu.business_location') : </strong>
                                @if($return->branch)
                                    {{ $return->branch->name.'/'.$return->branch->branch_code }}<b>(BL)</b>
                                @else
                                    {{ $generalSettings['business__shop_name'] }} <b>(HO)</b>
                                @endif
                            </li>
                            <li class="warehouse"><strong>{{ __('Return Stock Location') }} </strong>
                                @if ($return->warehouse)
                                    {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                                @elseif($return->branch)
                                    {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(BL)</b>
                                @else
                                    {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-1 text-left">
                        <ul class="list-unstyled">

                        </ul>
                    </div>
                    <div class="col-md-5 text-left">
                        <ul class="list-unstyled">
                            <li class="parent_purchase"><strong>@lang('menu.purchase_details') : </strong>  </li>
                            <li class="parent_purchase">
                                <strong>{{ __('P.Invoice ID') }} </strong>
                                {{ $return->purchase ? $return->purchase->invoice_id : 'N/A' }}
                            </li>
                            <li class="parent_purchase"><strong>@lang('menu.date') : </strong>
                                {{ $return->purchase ? $return->purchase->date : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div><br>
                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-secondary text-white text-start">
                                    <th class="text-start" scope="col">@lang('menu.sl')</th>
                                    <th class="text-start" scope="col">@lang('menu.product')</th>
                                    <th class="text-start" scope="col">@lang('menu.unit_cost')</th>
                                    <th class="text-start" scope="col">@lang('menu.return_quantity')</th>
                                    <th class="text-start" scope="col">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody class="purchase_return_product_list">
                                @foreach ($return->purchase_return_products as $return_product)
                                    @if ($return_product->return_qty > 0)
                                        <tr>
                                            <td class="text-start">{{ $loop->index + 1 }}</td>
                                            <td class="text-start">
                                                {{ $return_product->product->name }}
                                                @if ($return_product->variant)
                                                    -{{ $return_product->variant->variant_name }}
                                                @endif
                                                @if ($return_product->variant)
                                                    ({{ $return_product->variant->variant_code }})
                                                @else
                                                ({{ $return_product->product->product_code }})
                                                @endif
                                            </td>

                                            <td class="text-start">
                                                @if ($return_product->purchase_product)
                                                    {{ $return_product->purchase_product->net_unit_cost }}
                                                @else
                                                    @if ($return_product->variant)
                                                        {{ $return_product->variant->variant_cost_with_tax }}
                                                    @else
                                                        {{ $return_product->product->product_cost_with_tax }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start">
                                                {{ $return_product->return_qty }} ({{ $return_product->unit }})
                                            </td>

                                            <td class="text-start">
                                                {{ $return_product->return_subtotal }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th class="text-start">@lang('menu.total_return_amount') : </th>
                                    <td class="total_return_amount text-start">{{ $return->total_return_amount }}</td>
                                </tr>
                            </table>
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
<div class="purchase_return_print_template d-hide">
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
                        @if ($generalSettings['business__business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
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

                        <li><strong>{{ __('PR.Invoice ID') }} </strong>
                            <span class="return_invoice_id">{{ $return->invoice_id }}</span>
                        </li>

                        <li><strong>@lang('menu.return_date') : </strong>
                            <span class="return_date">{{ $return->date }}</span>
                        </li>

                        <li><strong>@lang('menu.supplier_name') : </strong>
                            {{ $return->supplier ? $return->supplier->name : $return->purchase->supplier->name }}
                        </li>

                        <li><strong>{{ __('Return Stock Location') }} </strong>
                            @if ($return->warehouse)

                                {{ $return->warehouse->warehouse_name.'/'.$return->warehouse->warehouse_code }}<b>(WH)</b>
                            @elseif($return->branch)

                                {{ $return->branch->name.'/'.$return->branch->branch_code }} <b>(B.L)</b>
                            @else

                                {{ $generalSettings['business__shop_name'] }}<b>(@lang('menu.head_office'))</b>
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
                        <th colspan="4" class="text-end">@lang('menu.total_return_amount') : {{ $generalSettings['business__currency'] }}</th>
                        <td colspan="2" class="text-end">{{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}</td>
                    </tr>

                    <tr>
                        <th colspan="4" class="text-end">@lang('menu.total_due') : {{ $generalSettings['business__currency'] }}</th>

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
                    <small>@lang('menu.software_by') : <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                </div>
            </div>
        @endif
    </div>
</div>
