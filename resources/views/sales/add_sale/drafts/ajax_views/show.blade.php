@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $defaultLayout = DB::table('invoice_layouts')->where('branch_id', null)->where('is_default', 1)->first();
    $invoiceLayout = $draft?->branch?->branchSetting?->addSaleInvoiceLayout ? $draft?->branch?->branchSetting?->addSaleInvoiceLayout : $defaultLayout;
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h6 class="modal-title" id="exampleModalLabel">
                    {{ __("Draft Details") }} ({{ __("Draft ID") }} : <strong>{{ $draft->draft_id }}</strong>)
                 </h6>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Customer") }} : - </strong></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Name") }} : </strong><span>{{ $draft->customer->name }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong><span>{{ $draft->customer->address }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Phone") }}: </strong><span>{{ $draft->customer->phone }}</span></li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($draft->date)) . ' ' . date($timeFormat, strtotime($draft->time)) }}</li>
                             <li style="font-size:11px!important;"><strong>{{ __('Draft ID') }} : </strong> {{ $draft->quotation_id }}</li>
                             <li style="font-size:11px!important;">
                                 <strong>{{ __("Created By") }} : </strong>
                                {{ $draft?->createdBy?->prefix .' '. $draft?->createdBy?->name .' '. $draft?->createdBy?->last_name }}
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Shop/Business") }} : </strong>
                                @if ($draft->branch_id)

                                    @if($draft?->branch?->parentBranch)

                                        {{ $draft?->branch?->parentBranch?->name . '(' . $draft?->branch?->area_name . ')'.'-('.$draft?->branch?->branch_code.')' }}
                                    @else

                                        {{ $draft?->branch?->name . '(' . $draft?->branch?->area_name . ')'.'-('.$draft?->branch?->branch_code.')' }}
                                    @endif
                                @else

                                    {{ $generalSettings['business__shop_name'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Email") }} : </strong>
                                @if ($draft->branch)

                                    {{ $draft->branch->email }}
                                @else

                                    {{ $generalSettings['business__email'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>
                                @if ($draft->branch)

                                    {{ $draft->branch->phone }}
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
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Quantity") }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Price Exc. Tax") }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Discount") }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Price Exc. Tax") }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($draft->saleProducts as $draftProduct)
                                        <tr>
                                            @php
                                                $variant = $draftProduct->variant ? ' - '.$draftProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $draftProduct->product->name . ' ' . $variant }}
                                                <small>{{ $draftProduct->description }}</small>
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $draftProduct->quantity.'/'.$draftProduct?->unit?->code_name }}</td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_exc_tax) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($draftProduct->unit_discount) }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ '(' . $draftProduct->unit_tax_percent.'%)=' . $draftProduct->unit_tax_amount }}
                                            </td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->unit_price_inc_tax) }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($draftProduct->subtotal) }}</td>
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                    <div class="col-md-5 offset-7">
                         <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($draft->net_total_amount) }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Sale Discount") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ $draft->order_discount_type == 1 ? '(Fixed)=' : '(%)=' }}{{ $draft->order_discount }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Sale Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ '('.$draft->order_tax_percent.'%)=' . $draft->order_tax_amount }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($draft->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($draft->total_invoice_amount) }}
                                   </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </div>
                 <hr>
                 <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important;"><strong>{{ __("Note") }}</strong></p>
                        <p style="font-size:11px!important;">{{ $draft->note }}</p>
                    </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('sale.drafts.edit', [$draft->id]) }}" class="btn btn-sm btn-secondary">{{ __("Edit") }}</a>
                            <button type="button" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __("Print Draft") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>

 <!-- Sales Order print templete-->
 @include('sales.add_sale.drafts.ajax_views.partials.print_modal_details')
 <!-- Sales Order print templete end-->
