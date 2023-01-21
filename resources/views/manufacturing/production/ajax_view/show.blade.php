@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.production_details') (@lang('menu.reference_id') : <strong>{{ $production->reference_no }}</strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.stored_location') : </strong>
                                @if ($production->warehouse_id)
                                    {{ $production->warehouse->warehouse_name.'/'.$production->warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    @if ($production->branch_id)
                                        {{ $production->branch->name.'/'.$production->branch->branch_code }}<b>(BL)</b>
                                    @else
                                        {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                    @endif
                                @endif
                            </li>
                            <li><strong>@lang('menu.ingredients_stock_location') : </strong>
                                @if ($production->stock_warehouse_id)
                                    {{ $production->stock_warehouse->warehouse_name.'/'.$production->stock_warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    @if ($production->stock_branch_id)
                                        {{ $production->stock_branch->name.'/'.$production->stock_branch->branch_code }}<b>(BL)</b>
                                    @else
                                        {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                    @endif
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('menu.production_item') : </strong>
                                {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                            </li>
                            <li>
                                <strong>@lang('menu.production_status') : </strong>
                                @if ($production->is_final == 1)
                                    <span class="text-success">@lang('menu.final')</span>
                                @else
                                    <span class="text-hold">@lang('menu.hold')</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.voucher_no') : </strong> {{ $production->reference_no }}</li>
                            <li><strong>@lang('menu.date') : </strong>{{ date($generalSettings['business__date_format'], strtotime($production->date)) . ' ' . date($timeFormat, strtotime($production->time)) }}</li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>{{ __('Ingredients List') }}</strong></p>
                        <div class="table-responsive">
                            <table class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white text-start">@lang('menu.ingredient_name')</th>
                                        <th class="text-white text-start">@lang('menu.input_qty')</th>
                                        <th class="text-white text-start">@lang('menu.unit_cost_inc_tax')({{ $generalSettings['business__currency'] }})</th>
                                        <th class="text-white text-start">@lang('menu.subtotal')({{ $generalSettings['business__currency'] }})</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_print_product_list">
                                    @foreach ($production->ingredients as $ingredient)
                                        <tr>
                                            @php
                                                $variant = $ingredient->variant_id ? ' ('.$ingredient->variant->variant_name.')' : '';
                                            @endphp

                                            <td class="text-start">{{ Str::limit($ingredient->product->name, 40).' '.$variant }}</td>
                                            <td class="text-start">{{ $ingredient->input_qty }}</td>
                                            <td class="text-start">
                                                {{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}
                                            </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>@lang('menu.production_quantity_and_total_cost')</strong></p>
                        <table class="table modal-table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-end">@lang('menu.output_quantity') : </th>
                                    <td class="text-end">
                                        {{ $production->quantity.'/'.$production->unit->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.wasted_quantity') : </th>
                                    <td class="text-end">
                                        {{ $production->wasted_quantity.'/'.$production->unit->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.final_quantity') : </th>
                                    <td class="text-end">
                                        {{ $production->total_final_quantity.'/'.$production->unit->code_name }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.additional_cost') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($production->production_cost) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.total_cost') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($production->total_cost) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 text-end">
                        <p><strong>{{ __('Production Items Costing And Pricing') }}</strong></p>
                        <table class="table modal-table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-end">@lang('menu.tax') : </th>
                                    <td class="text-end">
                                        {{ $production->tax ? $production->tax->tax_percent : 0 }}%
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.per_unit_cost_exc_tax') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($production->unit_cost_exc_tax) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.per_unit_cost_inc_tax') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($production->unit_cost_inc_tax) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.x_margin')(%) </th>
                                    <td class="text-end">
                                        {{ $production->x_margin }}%
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.selling_price_exc_tax') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($production->price_exc_tax) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
               <div class="row">
                   <div class="col-md-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('manufacturing.productions.edit', $production->id) }}" class="btn btn-sm btn-secondary">@lang('menu.edit')</a>
                        <button type="submit" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                   </div>
               </div>
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
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
<!-- Purchase print templete-->
<div class="production_print_template">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-12 text-center">
                    <h6>
                        @if ($production->branch_id)
                            {{ $production->branch->name.'/'.$production->branch->branch_code }}<b>(BL)</b>
                        @else
                            {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                        @endif
                    </h6>
                    <p style="width: 60%; margin:0 auto;">
                        @if ($production->branch_id)
                            {{ $production->branch->city.', '.$production->branch->state.', '.$production->branch->zip_code.', '.$production->branch->country }}<b>(BL)</b>
                        @else
                            {{ $generalSettings['business__address'] }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="heading_area">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4">
                    @if ($production->branch_id)
                        @if ($production->branch->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $production->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $production->branch->name }}</span>
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
                        <p style="margin-top: 10px;" class="bill_name"><strong>@lang('menu.manufacturing_bill')</strong></p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4">

                </div>
            </div>
        </div>

        <div class="purchase_and_deal_info pt-3">
            <div class="row">
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.stored_location') : </strong>
                            @if ($production->warehouse_id)
                                {{ $production->warehouse->warehouse_name.'/'.$production->warehouse->warehouse_code }}<b>(WH)</b>
                            @else
                                @if ($production->branch_id)
                                    {{ $production->branch->name.'/'.$production->branch->branch_code }}<b>(BL)</b>
                                @else
                                    {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                @endif
                            @endif
                        </li>
                        <li><strong>@lang('menu.ingredients_stock_location') : </strong>
                            @if ($production->stock_warehouse_id)
                                {{ $production->stock_warehouse->warehouse_name.'/'.$production->stock_warehouse->warehouse_code }}<b>(WH)</b>
                            @else
                                @if ($production->stock_branch_id)
                                    {{ $production->stock_branch->name.'/'.$production->stock_branch->branch_code }}<b>(BL)</b>
                                @else
                                    {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('menu.production_item') : </strong>
                            {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                        </li>
                        <li>
                            <strong>@lang('menu.production_status')</strong>
                            @if ($production->is_final == 1)
                                <span class="text-success">@lang('menu.final')</span>
                            @else
                                <span class="text-hold">@lang('menu.hold')</span>
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.voucher_no') : </strong> {{ $production->reference_no }}</li>
                        <li><strong>@lang('menu.date') : </strong>{{ date($generalSettings['business__date_format'], strtotime($production->date)) . ' ' . date($timeFormat, strtotime($production->time)) }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="purchase_product_table pt-3 pb-3">
            <p><strong>{{ __('Ingredients List') }}</strong></p>
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th scope="col">@lang('menu.ingredient_name')</th>
                        <th scope="col">@lang('menu.input_qty')</th>
                        <th scope="col">@lang('menu.unit_cost_inc_tax')({{ $generalSettings['business__currency'] }})</th>
                        <th scope="col">@lang('menu.subtotal')({{ $generalSettings['business__currency'] }})</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($production->ingredients as $ingredient)
                        <tr>
                            @php
                                $variant = $ingredient->variant_id ? ' ('.$ingredient->variant->variant_name.')' : '';
                            @endphp

                            <td>{{ Str::limit($ingredient->product->name, 40).' '.$variant }}</td>
                            <td>{{ $ingredient->input_qty }}</td>
                            <td>
                                {{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}
                            </td>
                            <td>{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br>
        <div class="row">
            <div class="col-md-6">
                <p><strong>@lang('menu.production_quantity_and_total_cost')</strong></p>
                <table class="table modal-table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th class="text-end">@lang('menu.output_quantity') </th>
                            <td class="text-end">
                                {{ $production->quantity.'/'.$production->unit->code_name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.wasted_quantity') </th>
                            <td class="text-end">
                                {{ $production->wasted_quantity.'/'.$production->unit->code_name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.final_quantity') </th>
                            <td class="text-end">
                                {{ $production->total_final_quantity.'/'.$production->unit->code_name }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.additional_cost') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($production->production_cost) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.total_cost') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($production->total_cost) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 text-end">
                <p><strong>{{ __('Production Items Costing And Pricing') }}</strong></p>
                <table class="table modal-table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th class="text-end">@lang('menu.tax') </th>
                            <td class="text-end">
                                {{ $production->tax ? $production->tax->tax_percent : '' }}%
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.per_unit_cost_exc_tax') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($production->unit_cost_exc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.per_unit_cost_inc_tax') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($production->unit_cost_inc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.x_margin')(%) </th>
                            <td class="text-end">
                                {{ $production->x_margin }}%
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">@lang('menu.selling_price_exc_tax') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($production->price_exc_tax) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-md-6">
                <h6>@lang('menu.checked_by') </h6>
            </div>

            <div class="col-md-6 text-end">
                <h6>@lang('menu.approved_by') </h6>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($production->reference_no, $generator::TYPE_CODE_128)) }}">
                <p>{{$production->reference_no}}</p>
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
<!-- production print templete end-->
