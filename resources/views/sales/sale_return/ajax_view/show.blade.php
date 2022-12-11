    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog col-65-modal">
          <div class="modal-content" >
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                  Sale return (@lang('menu.invoice_id') : <strong>{{ $saleReturn->invoice_id }}</strong>)
              </h5>
              <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li><strong>Return Details : </strong> </li>
                            <li><strong>Return Invoice ID : </strong> {{ $saleReturn->invoice_id }}</li>
                            <li><strong>Return Date : </strong> {{ $saleReturn->date }}</li>
                            <li><strong>Customer Name : </strong> {{ $saleReturn->customer ? $saleReturn->customer->name : 'Walk-In-Customer' }}</li>
                            <li><strong>Stock Location : </strong> {!! $saleReturn->branch ? $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code.'<b>(BL)</b>' : json_decode($generalSettings->business, true)['shop_name'].'<b>(HO)</b>' !!} </li>
                        </ul>
                    </div>
                    <div class="col-6 text-left">
                        <ul class="list-unstyled">
                            <li><strong>Parent Sale Details  </strong></li>
                            <li><strong>Invoice No : </strong> {{ $saleReturn->sale ? $saleReturn->sale->invoice_id : '' }}</li>
                            <li><strong>@lang('menu.date') : </strong> {{ $saleReturn->sale ? $saleReturn->sale->date : '' }}</li>
                        </ul>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th class="text-start">@lang('menu.sl')</th>
                                        <th class="text-start">@lang('menu.product')</th>
                                        <th class="text-start">@lang('menu.unit_price')</th>
                                        <th class="text-start">Return Quantity</th>
                                        <th class="text-start">@lang('menu.sub_total')</th>
                                    </tr>
                                </thead>
                                <tbody class="sale_return_product_list">
                                    @foreach ($saleReturn->sale_return_products as $sale_return_product)
                                        <tr>
                                            <td class="text-start">{{ $loop->index + 1 }}</td>
                                            <td class="text-start">
                                                {{ $sale_return_product->product->name }}

                                                @if ($sale_return_product->variant)

                                                    -{{ $sale_return_product->variant->variant_name }}
                                                @endif

                                                @if ($sale_return_product->variant)

                                                    ({{ $sale_return_product->variant->variant_code }})
                                                @else

                                                    ({{ $sale_return_product->product->product_code }})
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                {{ App\Utils\Converter::format_in_bdt($sale_return_product->unit_price_inc_tax)  }}
                                            </td>
                                            <td class="text-start">
                                                {{ $sale_return_product->return_qty }} ({{ $sale_return_product->unit }})
                                            </td>
                                            <td class="text-start">
                                                {{ $sale_return_product->return_subtotal }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table tabl-sm">
                                <tr>
                                    <th class="text-start">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-start net_total ">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start">Return Discount : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-start return_discount">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start">Total Return Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-start total_return_amount">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-start">@lang('menu.total_paid')/Refunded Amount : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-start total_return_amount">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_due_pay) }}
                                    </td>
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

    <!-- Sale print templete-->
    <div class="sale_return_print_template d-hide">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="heading text-center">
                            @if ($saleReturn->branch)
                                <h5 class="company_name">{{ $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code}}</h5>
                                <p class="company_address">
                                    {{ $saleReturn->branch->city }},
                                    {{ $saleReturn->branch->state }},
                                    {{ $saleReturn->branch->zip_code }},
                                    {{ $saleReturn->branch->country }},
                                </p>
                                <p class="company_phone">@lang('menu.phone') : {{ $saleReturn->branch->phone }}</p>
                            @else
                                <h5 class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h5>
                                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                                <p class="company_address">@lang('menu.phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                            @endif
                            <h6 class="bill_name">Sale Return Invoice</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Return Details : </strong> </li>
                            <li><strong>@lang('menu.invoice_id') : </strong>{{ $saleReturn->invoice }}</li>
                            <li><strong>Return Date : </strong>{{ $saleReturn->date }}</li>
                            <li><strong>Customer Name : </strong>{{ $saleReturn->customer ? $saleReturn->customer->name : 'Walk-In-Customer' }}</li>
                            <li><strong>Stock Location : </strong> {{$saleReturn->branch ? $saleReturn->branch->name.'/'.$saleReturn->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">

                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled float-right">
                            <li>
                                <strong>Sale Details </strong> </li>
                            <li>
                                <strong>Invoice No : </strong> {{ $saleReturn->sale ? $saleReturn->sale->invoice_id : '' }}
                            </li>
                            <li><strong>@lang('menu.date') : </strong>  {{ $saleReturn->sale ? $saleReturn->sale->date : '' }} </li>
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
                                <th class="text-start">@lang('menu.unit_price')</th>
                                <th class="text-start">Return Quantity</th>
                                <th class="text-start">@lang('menu.sub_total')</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody class="sale_return_print_product_list">
                        @foreach ($saleReturn->sale_return_products as $sale_return_product)

                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $sale_return_product->product->name }}

                                    @if ($sale_return_product->variant)

                                        -{{ $sale_return_product->variant->variant_name }}
                                    @endif

                                    @if ($sale_return_product->variant)

                                        ({{ $sale_return_product->variant->variant_code }})
                                    @else

                                        ({{ $sale_return_product->product->product_code }})
                                    @endif
                                </td>
                                <td class="text-start">
                                    {{ App\Utils\Converter::format_in_bdt($sale_return_product->unit_price_inc_tax) }}
                                </td>
                                <td class="text-start">
                                    {{ $sale_return_product->return_qty }} ({{ $sale_return_product->unit }})
                                </td>
                                <td class="text-start">
                                    {{ App\Utils\Converter::format_in_bdt($sale_return_product->return_subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-end" colspan="4"">@lang('menu.net_total_amount') :</th>
                            <td class="text-start" colspan="2">{{ App\Utils\Converter::format_in_bdt($saleReturn->net_total_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end" colspan="4">Return Discount :</th>
                            <td class="text-start" colspan="2">
                                @if ($saleReturn->return_discount_type == 1)
                                    {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }} (Fixed)
                                @else
                                    {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }} ({{ $saleReturn->return_discount}}%)
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end" colspan="4">Total Return Amount :</th>
                            <td class="text-start" colspan="2">{{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-end" colspan="4">@lang('menu.total_paid')/Refunded Amount :</th>
                            <td class="text-start" colspan="2">{{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_due_pay) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <br><br>
            <div class="note">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>Receiver's Signature</strong></h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6><strong>Signature Of seller</strong></h6>
                    </div>
                </div>
            </div>

            <div class="note">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd').</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale print templete end-->
