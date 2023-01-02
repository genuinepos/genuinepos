@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business']['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                     @lang('menu.po_details') (@lang('menu.reference_id') : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li><strong>@lang('menu.supplier') : - </strong></li>
                             <li><strong>@lang('menu.name') :</strong> {{ $purchase->supplier->name }}</li>
                             <li><strong>@lang('menu.address') : </strong> {{ $purchase->supplier->address }}</li>
                             <li><strong>@lang('menu.tax_number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                             <li><strong>@lang('menu.phone') : </strong> {{ $purchase->supplier->phone }}</li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>@lang('menu.purchase_from') : </strong></li>
                             <li><strong>@lang('menu.business_location') : </strong>
                                @if ($purchase->branch_id)
                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}(<b>@lang('menu.branch_concern')</b>)
                                @else
                                    {{ $generalSettings['business']['shop_name'] }} (<b>@lang('menu.head_office')</b>)
                                @endif
                            </li>

                             <li><strong>@lang('menu.phone') : </strong>
                                @if ($purchase->branch)

                                    {{ $purchase->branch->phone }}, <br>
                                @elseif($purchase->warehouse_id)

                                    {{ $purchase->warehouse->phone }}
                                @else

                                    {{ $generalSettings['business']['phone'] }}
                                @endif
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                            <li><strong>@lang('menu.po_invoice_id') : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>{{ __('PO Date') }} : </strong> {{ date($generalSettings['business']['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                            <li><strong>{{ __('Delivery') }} : </strong> {{$purchase->delivery_date ? date($generalSettings['business']['date_format'], strtotime($purchase->date)) : '' }}</li>
                            <li><strong>@lang('menu.purchases_status') : </strong> <span class="badge bg-primary">@lang('menu.ordered')</span></li>
                            <li><strong>@lang('menu.receiving_status') : </strong>
                                @if ($purchase->po_receiving_status == 'Pending')

                                    <span class="badge bg-danger">@lang('menu.pending')</span>
                                @elseif ($purchase->po_receiving_status == 'Completed')

                                    <span class="badge bg-success">@lang('menu.completed')</span>
                                @else

                                    <span class="badge bg-primary">@lang('menu.partial')</span>
                                @endif
                            </li>
                            <li><strong>@lang('menu.payment_status') : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">@lang('menu.paid')</span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">@lang('menu.partial')</span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">@lang('menu.due')</span>
                                @endif
                             </li>
                             <li>
                                 <strong>@lang('menu.created_by') : </strong>
                                {{ $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name }}
                             </li>
                         </ul>
                     </div>
                 </div>
                 <br>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="table-responsive">
                             <table id="" class="table modal-table table-sm table-striped">
                                 <thead>
                                     <tr class="bg-secondary">
                                         <th class="text-white text-start">@lang('menu.product')</th>
                                         <th class="text-white text-start">@lang('menu.ordered_quantity')</th>
                                         <th class="text-white text-start">@lang('menu.unit_cost')({{ $generalSettings['business']['currency']}}) (@lang('menu.before_discount'))</th>
                                         <th class="text-white text-start">@lang('menu.unit_cost')({{ $generalSettings['business']['currency']}})</th>
                                         <th class="text-white text-start">@lang('menu.unit_cost')({{ $generalSettings['business']['currency']}}) (@lang('menu.before_tax'))</th>
                                         <th class="text-white text-start">@lang('menu.subtotal')({{ $generalSettings['business']['currency']}}) (@lang('menu.before_tax'))</th>
                                         <th class="text-white text-start">@lang('menu.tax')(%)</th>
                                         <th class="text-white text-start">@lang('menu.unit_cost')({{ $generalSettings['business']['currency']}}) (@lang('menu.after_tax'))</th>
                                         <th class="text-white text-start">@lang('menu.subtotal')({{ $generalSettings['business']['currency']}})</th>
                                         <th class="text-white text-start">@lang('menu.pending_qty')</th>
                                         <th class="text-white text-start">@lang('menu.received_qty')</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchase_order_products as $product)
                                        <tr>
                                            @php
                                                $variant = $product->variant ? '('.$product->variant->variant_name.')' : '';
                                            @endphp

                                            <td class="text-start">{{ $product->product->name.' '.$variant }}</td>
                                            <td class="text-start">{{ $product->order_quantity }}</td>
                                            <td class="text-start">
                                                {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                                            </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_cost_with_discount) }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->subtotal) }}</td>
                                            <td class="text-start">{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                                            <td class="text-start text-danger">{{ $product->pending_quantity }}</td>
                                            <td class="text-start text-success">{{ $product->received_quantity }}</td>
                                            @if (count($product->receives) > 0)

                                                <tr>
                                                    <td colspan="3" class="text-center"><strong>@lang('menu.receipt_details') ➡</strong></td>

                                                    <td colspan="8">
                                                        <table class="table modal-table table-sm table-striped">
                                                            <thead>
                                                                <tr class="bg-secondary">
                                                                    <th class="text-white">@lang('menu.challan_no')</th>
                                                                    <th class="text-white">@lang('menu.lot_number')</th>
                                                                    <th class="text-white">Received Date</th>
                                                                    <th class="text-white">Received Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($product->receives as $receive)
                                                                    <tr>
                                                                        <td>{{ $receive->purchase_challan }}</td>

                                                                        <td>{{ $receive->lot_number }}</td>

                                                                        <td>{{ date($generalSettings['business']['date_format'], strtotime($receive->received_date)) }}</td>

                                                                        <td>{{ $receive->qty_received }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="payment_table">
                             <div class="table-responsive">
                                <table class="table modal-table table-striped table-sm">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <th class="text-start">@lang('menu.date')</th>
                                            <th class="text-start">@lang('menu.voucher_no')</th>
                                            <th class="text-start">@lang('menu.method')</th>
                                            <th class="text-start">@lang('menu.type')</th>
                                            <th class="text-start">@lang('menu.account')</th>
                                            <th class="text-end">
                                                Amount({{ $generalSettings['business']['currency'] }})
                                            </th>
                                            <th>@lang('menu.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                       @if (count($purchase->purchase_payments) > 0)

                                           @foreach ($purchase->purchase_payments as $payment)

                                               <tr data-info="{{ $payment }}">
                                                   <td class="text-start">{{ date($generalSettings['business']['date_format'], strtotime($payment->date)) }}</td>
                                                   <td class="text-start">{{ $payment->invoice_id }}</td>
                                                   <td class="text-start">{{ $payment->pay_mode }}</td>
                                                   <td class="text-start">
                                                        @if ($payment->is_advanced == 1)

                                                            <b>@lang('menu.po_advance_payment')</b>
                                                        @else

                                                            {{ $payment->payment_type == 1 ? 'Payment' : 'Received Return Amt.' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-start">
                                                        {{ $payment->account ? $payment->account->name.' (A/C'.$payment->account->account_number.')' : 'N/A' }}
                                                    </td>
                                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}</td>
                                                    <td class="text-start">
                                                       @if (auth()->user()->branch_id == $purchase->branch_id)

                                                           @if ($payment->payment_type == 1)

                                                               <a href="{{ route('purchases.payment.edit', $payment->id) }}" id="edit_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @else

                                                               <a href="{{ route('purchases.return.payment.edit', $payment->id) }}" id="edit_return_payment" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                                           @endif

                                                           <a href="{{ route('purchases.payment.details', $payment->id) }}" id="payment_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                                       @else
                                                           ......
                                                       @endif
                                                   </td>
                                               </tr>
                                           @endforeach
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
                                    <th class="text-end">@lang('menu.net_total_amount') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.order_discount') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.order_tax') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.shipment_charge') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end"><b></b>
                                           {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.grand_total') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.paid') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.due') : {{ $generalSettings['business']['currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
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
                             <p><b>@lang('menu.shipping_details')</b> : </p>
                             <p class="shipping_details">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p><b>@lang('menu.purchase_not')</b> : </p>
                             <p class="purchase_note">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('purchases.po.receive.process', $purchase->id) }}" class="btn btn-sm btn-secondary"> <i class="fas fa-check-double"></i> PO To Receive</a>
                        <a href="{{ route('purchases.order.supplier.copy.print', $purchase->id) }}" id="print_supplier_copy" class="btn btn-sm btn-info text-white"> <i class="fas fa-print"></i> Print Supplier Copy</a>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                        <button type="submit" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
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
 <!-- Purchase Order print templete-->
    <div class="purchase_print_template d-hide">
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
                            @if ($generalSettings['business']['business_logo'] != null)

                                <img src="{{ asset('uploads/business_logo/' . $generalSettings['business']['business_logo']) }}" alt="logo" class="logo__img">
                            @else

                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business']['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <h3 class="bill_name">@lang('menu.purchase_order')</h3>
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
                            <li><strong>@lang('menu.supplier') : - </strong></li>
                            <li><strong>@lang('menu.name') :</strong>{{ $purchase->supplier->name }}</li>
                            <li><strong>@lang('menu.address') : </strong>{{ $purchase->supplier->address }}</li>
                            <li><strong>@lang('menu.tax_number') : </strong> {{ $purchase->supplier->tax_number }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.purchase_from') : </strong></li>
                            <li>
                                <strong>@lang('menu.business_location') : </strong>
                                @if ($purchase->branch)

                                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                                @else

                                    {{ $generalSettings['business']['shop_name'] }} (<b>HO</b>)
                                @endif
                            </li>
                            <li><strong>{{ __('Ordered Location') }} : </strong>
                                @if ($purchase->warehouse_id )

                                    {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_code }}
                                    (<b>WH</b>)
                                @elseif($purchase->branch_id)

                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                    (<b>B.L</b>)
                                @else

                                    {{ $generalSettings['business']['shop_name'] }} (<b>@lang('menu.head_office')</b>)
                                @endif
                            </li>
                            <li><strong>@lang('menu.phone') : </strong>
                                @if ($purchase->branch)

                                    {{ $purchase->branch->phone }}
                                @elseif($purchase->warehouse_id)

                                    {{ $purchase->warehouse->phone }}.
                                @else

                                    {{ $generalSettings['business']['phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.po_invoice_id') : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('menu.purchase_date') : </strong>{{ date($generalSettings['business']['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                            <li><strong>@lang('menu.delivery_date') : </strong>{{ $purchase->delivery_date ? date($generalSettings['business']['date_format'], strtotime($purchase->delivery_date)) : '' }}</li>

                            <li><strong>@lang('menu.purchases_status') : </strong>@lang('menu.ordered')</li>
                            <li><strong>@lang('menu.receiving_status') : </strong>{{ $purchase->po_receiving_status }}</li>
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
                            <th scope="col">@lang('menu.product')</th>
                            <th scope="col">@lang('menu.ordered_quantity')</th>
                            <th scope="col">@lang('menu.unit_cost')({{ $generalSettings['business']['currency'] }})</th>
                            <th scope="col">@lang('menu.unit_cost')({{ $generalSettings['business']['currency'] }})</th>
                            <th scope="col">@lang('menu.tax')(%)</th>
                            <th scope="col">@lang('menu.subtotal')({{ $generalSettings['business']['currency'] }})</th>
                            <th scope="col">@lang('menu.pending_qty')</th>
                            <th scope="col">@lang('menu.received_qty')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchase_order_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                                @endphp

                                <td>
                                    {{ Str::limit($product->product->name, 25).' '.$variant }}
                                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                                </td>

                                <td>{{ $product->order_quantity }}</td>
                                <td>
                                    {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                                </td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                <td>{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                                <td>{{ $product->pending_quantity }}</td>
                                <td>{{ $product->received_quantity }}</td>

                                @if (count($product->receives) > 0)
                                    <tr>
                                        <td colspan="3" class="text-center"><strong>@lang('menu.receipt_details') ➡</strong></td>

                                        <td colspan="8">
                                            <table class="table modal-table table-sm table-bordered">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>@lang('menu.challan_no')</th>
                                                        <th>@lang('menu.lot_number')</th>
                                                        <th>Received Date</th>
                                                        <th>Received Quantity</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($product->receives as $receive)
                                                        <tr class="text-end">
                                                            <td>{{ $receive->purchase_challan }}</td>

                                                            <td>{{ $receive->lot_number }}</td>

                                                            <td>{{ date($generalSettings['business']['date_format'], strtotime($receive->received_date)) }}</td>

                                                            <td>{{ $receive->qty_received }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-6">
                    <p><strong>@lang('menu.order_note') :</strong> </p>
                    <p>{{ $purchase->purchase_note }}</p><br>
                    <p><strong>@lang('menu.shipment_details') :</strong> </p>
                    <p>{{ $purchase->shipment_details }}</p>
                </div>

                <div class="col-6">
                    <table class="table modal-table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.net_total_amount') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.order_discount') :
                                    {{ $generalSettings['business']['currency'] }}
                                </th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.order_tax') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->purchase_tax_amount).' ('.$purchase->purchase_tax_percent.'%)' }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.shipment_charge') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.grand_total') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.paid') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="11" class="text-end">@lang('menu.due') : {{ $generalSettings['business']['currency'] }}</th>
                                <td colspan="2" class="text-end">
                                    {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>@lang('menu.checked_by') : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>@lang('menu.approved_by') : </h6>
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
