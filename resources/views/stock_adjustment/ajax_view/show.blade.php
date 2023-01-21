@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog col-80-modal">
      <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">@lang('menu.stock_adjustment_details') (@lang('menu.reference_no') : <strong>{{ $adjustment->invoice_id }}</strong>)</h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('menu.business_location') : </strong>
                            {{
                                $adjustment->branch ? $adjustment->branch->name.'/'.$adjustment->branch->branch_code : $generalSettings['business__shop_name'].' (HO)'
                            }}
                        </li>

                        @if ($adjustment->warehouse_id)
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(WAREHOUSE)</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $adjustment->warehouse->phone}}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $adjustment->warehouse->address}}</li>
                        @elseif($adjustment->branch_id)
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }} <b>(BRANCH)</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $adjustment->branch->phone}}</li>
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $adjustment->branch->city}}, {{ $adjustment->branch->state}}, {{ $adjustment->branch->zip_code}}, {{ $adjustment->branch->country}}
                            </li>
                        @else
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $generalSettings['business__shop_name'] }} <b>(@lang('menu.head_office'))</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $generalSettings['business__phone'] }}</li>
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $generalSettings['business__address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-6 text-left">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.date') : </strong>{{ date($generalSettings['business__date_format'], strtotime($adjustment->date)) . ' ' . $adjustment->time }}</li>
                        <li><strong>@lang('menu.reference_no') : </strong> {{ $adjustment->invoice_id }}</li>
                        <li><strong>@lang('menu.type') : </strong>
                            {!! $adjustment->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>' !!}
                        </li>
                        <li><strong>@lang('menu.created_by') : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div><br>

            <div class="row">
                <div class="table-responsive">
                    <table id="" class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-secondary text-white text-start">
                                <th class="text-start">@lang('menu.sl')</th>
                                <th class="text-start">@lang('menu.product')</th>
                                <th class="text-start">@lang('menu.quantity')</th>
                                <th class="text-start">@lang('menu.unit_cost_inc_tax')</th>
                                <th class="text-start">@lang('menu.sub_total')</th>
                            </tr>
                        </thead>
                        <tbody class="adjustment_product_list">
                            @foreach ($adjustment->adjustment_products as $product)
                                <tr>
                                    <td class="text-start">{{ $loop->index + 1 }}</td>
                                    @php
                                        $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                                    @endphp
                                    <td class="text-start">{{ $product->product->name.$variant }}</td>
                                    <td class="text-start">{{ $product->quantity.' ('.$product->unit.')' }}</td>
                                    <td class="text-start">
                                        {{ $generalSettings['business__currency'].' '.$product->unit_cost_inc_tax }}
                                    </td>
                                    <td class="text-start">{{ $generalSettings['business__currency'].' '.$product->subtotal }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="payment_table">
                        <div class="table-responsive">
                           <table class="table modal-table table-striped table-sm">
                               <thead>
                                   <tr class="bg-secondary text-white">
                                       <th>@lang('menu.date')</th>
                                       <th>@lang('menu.voucher_no')</th>
                                       <th>@lang('menu.method')</th>
                                       <th>@lang('menu.account')</th>
                                       <th>
                                           @lang('menu.recovered_amount')({{ $generalSettings['business__currency'] }})
                                       </th>
                                   </tr>
                               </thead>
                               <tbody id="p_details_payment_list">
                                  @if ($adjustment->recover)
                                    <tr>
                                        <td>{{ date($generalSettings['business__date_format'], strtotime($adjustment->recover->report_date)) }}</td>
                                        <td>{{ $adjustment->recover->voucher_no }}</td>
                                        <td>{{ $adjustment->recover->paymentMethod ? $adjustment->recover->paymentMethod->name : '' }}</td>
                                        <td>
                                            {{ $adjustment->recover->account ? $adjustment->recover->account->name : 'N/A' }}
                                        </td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($adjustment->recover->recovered_amount) }}</td>
                                    </tr>
                                  @else
                                      <tr>
                                          <td colspan="7" class="text-center">@lang('menu.no_data_found')</td>
                                      </tr>
                                  @endif
                               </tbody>
                           </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <tr>
                                <th class="text-start">@lang('menu.net_total_amount') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'].' '.$adjustment->net_total_amount}}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-start">@lang('menu.recovered_amount') : </th>
                                <td class="text-start">
                                    {{ $generalSettings['business__currency'].' '.$adjustment->recovered_amount }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div><br>

            <hr class="p-0 m-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="details_area">
                        <h6>@lang('menu.reason') : </h6>
                        <p class="reason">{{ $adjustment->reason }}</p>
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

<!-- Adjustment print templete-->
<div class="adjustment_print_template d-hide">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        @if ($adjustment->branch)
                            <h5 class="branch_name">{{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }}</h5>
                            <p class="address">{{ $adjustment->branch->city }}, {{ $adjustment->branch->state }},
                                {{ $adjustment->branch->zip_code }}, {{ $adjustment->branch->country }}</p>
                            <p class="branch_phone"><b>@lang('menu.phone')</b> : {{ $adjustment->branch->phone }}</p>
                            <p class="branch_email">{{ $adjustment->branch->email }}</p>
                        @else
                            <h5 class="business_name">{{ $generalSettings['business__shop_name'] }}</h5>
                            <p class="address">{{ $generalSettings['business__address'] }}</p>
                            <p class="branch_phone"><b>@lang('menu.phone')</b> : {{ $generalSettings['business__phone'] }}</p>
                            <p class="branch_email"><b>@lang('menu.email')</b> : {{ $generalSettings['business__email'] }}</p>
                        @endif
                        <h6 class="bill_name">@lang('menu.stock_adjustment_details')</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-8">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.date') : </strong>{{ date($generalSettings['business__date_format'], strtotime($adjustment->date)) . ' ' . $adjustment->time }}</li>
                        <li><strong>@lang('menu.reference_no') : </strong>{{ $adjustment->invoice_id }}</li>
                          @if ($adjustment->warehouse_id)
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $adjustment->warehouse->warehouse_name.'/'.$adjustment->warehouse->warehouse_code }} <b>(@lang('menu.warehouse'))</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $adjustment->warehouse->phone }}</li>
                            <li><strong>@lang('menu.address') : </strong> {{ $adjustment->warehouse->address }}</li>
                        @elseif($adjustment->branch_id)
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $adjustment->branch->name.'/'.$adjustment->branch->branch_code }} <b>(BRANCH)</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $adjustment->branch->phone}}</li>
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $adjustment->branch->city}}, {{ $adjustment->branch->state}}, {{ $adjustment->branch->zip_code}}, {{ $adjustment->branch->country}}
                            </li>
                        @else
                            <li>
                                <strong>@lang('menu.adjustment_location') : </strong>
                                {{ $generalSettings['business__shop_name'] }} <b>(@lang('menu.head_office'))</b>
                            </li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $generalSettings['business__phone'] }}</li>
                            <li><strong>@lang('menu.address') : </strong>
                                {{ $generalSettings['business__address'] }}
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-4">
                    <ul class="list-unstyled float-right">
                        <li>
                            <strong>@lang('menu.type') : </strong>
                            {{ $adjustment->type == 1 ? 'Normal' : 'Abnormal' }}
                        </li>
                        <li>
                            <strong>@lang('menu.created_by') : </strong>
                            {{ $adjustment->admin ? $adjustment->admin->prefix.' '.$adjustment->admin->name.' '.$adjustment->admin->last_name : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th scope="col" class="text-start">@lang('menu.sl')</th>
                            <th scope="col" class="text-start">@lang('menu.product')</th>
                            <th scope="col" class="text-start">@lang('menu.quantity')</th>
                            <th scope="col" class="text-start">@lang('menu.unit_cost_inc_tax')</th>
                            <th scope="col" class="text-start">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="adjustment_print_product_list">
                    @foreach ($adjustment->adjustment_products as $product)
                        <tr>
                            <td class="text-start">{{ $loop->index + 1 }}</td>
                            @php
                                $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                            @endphp
                            <td class="text-start">{{ $product->product->name.$variant }}</td>
                            <td class="text-start">{{ $product->quantity.' ('.$product->unit.')' }}</td>
                            <td class="text-start">
                                {{ $generalSettings['business__currency'].' '.$product->unit_cost_inc_tax }}
                            </td>
                            <td class="text-start">{{ $generalSettings['business__currency'].' '.$product->subtotal }} </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">@lang('menu.net_total_amount') : </th>
                        <td class="text-start">
                            {{ $generalSettings['business__currency'].' '.$adjustment->net_total_amount}}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">@lang('menu.recovered_amount') : </th>
                        <td class="text-start">
                            {{ $generalSettings['business__currency'].' '.$adjustment->recovered_amount }}
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
                <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($adjustment->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{$adjustment->invoice_id}}</p>
            </div>
        </div>

        @if (env('PRINT_SD_OTHERS') == true)
            <div class="print_footer">
                <div class="text-center">
                    <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Adjustment print templete end-->
