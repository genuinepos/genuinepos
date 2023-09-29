@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h6 class="modal-title" id="exampleModalLabel">
                    {{ __("Sale Details") }} ({{ __("Invoice ID") }} : <strong>{{ $sale->invoice_id }}</strong>)
                 </h6>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Customer") }} : - </strong></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Name") }} : </strong><span>{{ $sale->customer->name }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong><span>{{ $sale->customer->address }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Phone") }}: </strong><span>{{ $sale->customer->phone }}</span></li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</li>
                             <li style="font-size:11px!important;"><strong>{{ __('Invoice ID') }} : </strong> {{ $sale->invoice_id }}</li>

                             <li style="font-size:11px!important;"><strong>{{ __("Payment Status") }} : </strong>
                                @php
                                    $receivable = $sale->total_invoice_amount - $sale->sale_return_amount;
                                @endphp
                                @if ($sale->due <= 0)
                                     <span>{{ __("Paid") }}</span>
                                @elseif($sale->due > 0 && $sale->due < $receivable)
                                    <span>{{ __("Partial") }}</span>
                                @elseif($receivable == $sale->due)
                                    <span>{{ __("Due") }}</span>
                                @endif
                             </li>

                             <li style="font-size:11px!important;"><strong>{{ __("Shipment Status") }} : </strong>
                                @if ($sale->shipment_status == App\Enums\ShipmentStatus::NoStatus->value)
                                    <span>{{ __('Not-Available') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Ordered->value)
                                    <span>{{ __("Ordered") }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Packed->value)
                                    <span>{{ __('Packed') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Shipped->value)
                                    <span>{{ __('Shipped') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Delivered->value)
                                    <span>{{ __('Delivered') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Cancelled->value)
                                    <span>{{ __('Cancelled') }}</span>
                                @elseif($sale->shipment_status == App\Enums\ShipmentStatus::Completed->value)
                                    <span>{{ __('Cancelled') }}</span>
                                @endif
                            </li>

                             <li style="font-size:11px!important;">
                                 <strong>{{ __("Created By") }} : </strong>
                                {{ $sale?->createdBy?->prefix .' '. $sale?->createdBy?->name .' '. $sale?->createdBy?->last_name }}
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Shop/Business") }} : </strong>
                                @if ($sale->branch_id)

                                    @if($sale?->branch?->parentBranch)

                                        {{ $sale?->branch?->parentBranch?->name . '(' . $sale?->branch?->area_name . ')'.'-('.$sale?->branch?->branch_code.')' }}
                                    @else

                                        {{ $sale?->branch?->name . '(' . $sale?->branch?->area_name . ')'.'-('.$sale?->branch?->branch_code.')' }}
                                    @endif
                                @else

                                    {{ $generalSettings['business__shop_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Email") }} : </strong>
                                @if ($sale->branch)

                                    {{ $sale->branch->email }}
                                @else

                                    {{ $generalSettings['business__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>
                                @if ($sale->branch)

                                    {{ $sale->branch->phone }}
                                @else

                                    {{ $generalSettings['business__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="" class="table modal-table table-sm">
                                 <thead>
                                     <tr class="bg-secondary">
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("S/L") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Product") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Stock Location") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Quantity") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Price Exc. Tax") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Discount") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Price Exc. Tax") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($sale->saleProducts as $saleProduct)
                                        <tr>
                                            @php
                                                $variant = $saleProduct->variant ? ' - '.$saleProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $saleProduct->product->name.' '.$variant }}
                                                <small>{{ $saleProduct->description }}</small>
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                @if ($saleProduct?->warehouse)
                                                    {{ $saleProduct?->warehouse?->warehouse_name.'/'.$saleProduct?->warehouse?->warehouse_code.'-(WH)' }}
                                                @else
                                                    @if ($saleProduct->branch_id)

                                                        @if($saleProduct?->branch?->parentBranch)

                                                            {{ $saleProduct?->branch?->parentBranch?->name . '(' . $saleProduct?->branch?->area_name . ')'.'-('.$saleProduct?->branch?->branch_code.')' }}
                                                        @else

                                                            {{ $saleProduct?->branch?->name . '(' . $saleProduct?->branch?->area_name . ')'.'-('.$saleProduct?->branch?->branch_code.')' }}
                                                        @endif
                                                    @else

                                                        {{ $generalSettings['business__shop_name'] }}
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $saleProduct->quantity.'/'.$saleProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_discount) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ '(' . $saleProduct->unit_tax_percent.'%)=' . $saleProduct->unit_tax_amount }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax) }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</td>
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __("Recipts Against Sale") }}</p>
                        @include('sales.add_sale.ajax_views.partials.sale_details_receipt_list')
                    </div>

                    <div class="col-md-5">
                         <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Sale Discount") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ $sale->order_discount_type == 1 ? '(Fixed)=' : '(%)=' }}{{ $sale->order_discount }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Sale Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ '('.$sale->order_tax_percent.'%)=' . $sale->order_tax_amount }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Total Invoice Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                           {{ App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Received Amount") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Due (On Invoice)") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Current Balance") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt(0) }}
                                   </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><strong>{{ __("Shipment Details") }}</strong></p>
                             <p class="shipping_details" style="font-size:11px!important;">{{ $sale->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><strong>{{ __("Sale Note") }}</strong></p>
                             <p class="purchase_note" style="font-size:11px!important;">{{ $sale->note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('sales.edit', [$sale->id]) }}" class="btn btn-sm btn-secondary">{{ __("Edit") }}</a>
                            <a href="{{ route('sale.shipments.print.packing.slip', [$sale->id]) }}" class="footer_btn btn btn-sm btn-success" id="printPackingSlipBtn">{{ __("Print Packing Slip") }}</a>
                            <a href="{{ route('sales.print.challan', [$sale->id]) }}" class="footer_btn btn btn-sm btn-success" id="PrintChallanBtn">{{ __("Print Challan") }}</a>
                            <button type="button" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __("Print Invoice") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>

 <!-- Sale print templete-->
 @include('sales.add_sale.ajax_views.partials.print_modal_details')
 <!-- Sale print templete end-->

