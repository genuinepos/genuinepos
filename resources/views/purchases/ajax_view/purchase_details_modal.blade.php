@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                     Purchase Details (Reference ID : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li><strong>@lang('menu.supplier') : - </strong></li>
                             <li><strong>@lang('menu.name') :</strong> <span
                                     class="supplier_name">{{ $purchase->supplier->name }}</span></li>
                             <li><strong>@lang('menu.address') : </strong> <span
                                     class="supplier_address">{{ $purchase->supplier->address }}</span></li>
                             <li><strong>Tax Number : </strong> <span
                                     class="supplier_tax_number">{{ $purchase->supplier->tax_number }}</span></li>
                             <li><strong>@lang('menu.phone') : </strong> <span
                                     class="supplier_phone">{{ $purchase->supplier->phone }}</span></li>
                         </ul>
                     </div>
                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>Purchase From : </strong></li>
                             <li><strong>@lang('menu.business_location') : </strong>
                                @if ($purchase->branch_id)
                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}(<b>BL</b>)
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                                @endif
                            </li>

                             <li><strong>@lang('menu.phone') : </strong>
                                @if ($purchase->branch)
                                    {{ $purchase->branch->phone }}, <br>
                                @elseif($purchase->warehouse_id)
                                    {{ $purchase->warehouse->phone }}
                                @else
                                    {{ json_decode($generalSettings->business, true)['phone'] }}
                                @endif
                             </li>
                         </ul>
                     </div>
                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li><strong>@lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                             <li><strong>P.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>

                             <li>
                                <strong>Purchase Status : </strong>
                                @if ($purchase->purchase_status == 1)
                                    <span class="badge bg-success">Purchased</span>
                                @elseif($purchase->purchase_status == 2){
                                    <span class="badge bg-warning text-white">Pending</span>
                                @else
                                    <span class="badge bg-primary">Purchased By Order</span>
                                @endif
                             </li>

                             <li><strong>Payment Status : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">Paid</span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">Partial</span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">Due</span>
                                @endif
                             </li>
                             <li>
                                 <strong>@lang('menu.created_by') : </strong>
                                {{ $purchase->admin ? $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name : 'N/A' }}
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
                                         <th class="text-white text-start">@lang('menu.quantity')</th>
                                         <th class="text-white text-start">Unit Cost(Before Discount)</th>
                                         <th class="text-white text-start">Unit Discount</th>
                                         <th class="text-white text-start">Unit Cost(Before Tax)</th>
                                         <th class="text-white text-start">SubTotal (Before Tax)</th>
                                         <th class="text-white text-start">Tax(%)</th>
                                         <th class="text-white text-start">Unit Cost(After Tax)</th>
                                         <th class="text-white text-start">Unit Selling Price</th>
                                         <th class="text-white text-start">SubTotal</th>
                                         <th class="text-white text-start">Lot Number</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchase_products as $product)
                                        <tr>
                                            @php
                                                $variant = $product->variant ? '('.$product->variant->variant_name.')' : '';
                                            @endphp

                                            <td class="text-start">{{ $product->product->name.' '.$variant }}</td>
                                            <td class="text-start">{{ $product->quantity }}</td>
                                            <td class="text-start">
                                                {{ json_decode($generalSettings->business, true)['currency'].''.$product->unit_cost }}
                                            </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->unit_cost_with_discount) }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->subtotal) }}</td>
                                            <td class="text-start">{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }} </td>
                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->product->product_price) }}</td>

                                            <td class="text-start">{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                                            <td class="text-start">{{ $product->lot_no ? $product->lot_no : '' }}</td>
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
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.voucher_no')</th>
                                            <th>@lang('menu.method')</th>
                                            <th>@lang('menu.type')</th>
                                            <th>@lang('menu.account')</th>
                                            <th>
                                                Amount({{ json_decode($generalSettings->business, true)['currency'] }})
                                            </th>
                                            <th class="action_hideable">@lang('menu.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                       @if (count($purchase->purchase_payments) > 0)
                                           @foreach ($purchase->purchase_payments as $payment)
                                               <tr data-info="{{ $payment }}">
                                                   <td>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}</td>
                                                   <td>{{ $payment->invoice_id }}</td>
                                                   <td>{{ $payment->pay_mode }}</td>
                                                   <td>
                                                        @if ($payment->is_advanced == 1)
                                                            <b>@lang('menu.po_advance_payment')</b>
                                                        @else
                                                            {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $payment->account ? $payment->account->name.' (A/C'.$payment->account->account_number.')' : 'N/A' }}
                                                    </td>
                                                    <td>{{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}</td>
                                                    <td class="action_hideable">
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
                                    <th class="text-start">Net Total Amount</th>
                                    <td class="text-start">
                                        <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Purchase Discount</th>
                                    <td class="text-start">
                                       <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                           {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Purchase Tax</th>
                                    <td class="text-start">
                                       <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                           {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Shipment Charge</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                           {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-start">Grand Total</th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                           {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-start">Paid : </th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-start">@lang('menu.due') : </th>
                                    <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b>
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
                             <p><b>Shipping Details</b> : </p>
                             <p class="shipping_details">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p><b>Purchase Note</b> : </p>
                             <p class="purchase_note">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('purchases.edit', [$purchase->id, 'purchased']) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" class="footer_btn btn btn-sm btn-success print_btn">@lang('menu.print')</button>
                        </div>
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
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <h1 class="bill_name">Purchase Invoice</h1>
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
                            <li><strong>Namne : </strong>{{ $purchase->supplier->name }}</li>
                            <li><strong>@lang('menu.address') : </strong>{{ $purchase->supplier->address }}</li>
                            <li><strong>Tax Number : </strong> {{ $purchase->supplier->tax_number }}</li>
                            <li><strong>@lang('menu.phone') : </strong> {{ $purchase->supplier->phone }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>Purchase From : </strong></li>
                            <li>
                                <strong>@lang('menu.business_location') : </strong>
                                @if ($purchase->branch)
                                    {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' <b>(BL)</b>' !!}
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                                @endif
                            </li>
                            <li><strong>Stored Location : </strong>
                                @if ($purchase->warehouse_id )
                                    {{ $purchase->warehouse->warehouse_name . '/' . $purchase->warehouse->warehouse_code }}
                                    (<b>WH</b>)
                                @elseif($purchase->branch_id)
                                    {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}
                                    (<b>B.L</b>)
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>HO</b>)
                                @endif
                            </li>
                            <li><strong>@lang('menu.phone') : </strong>
                                @if ($purchase->branch)
                                    {{ $purchase->branch->phone }}
                                @elseif($purchase->warehouse_id)
                                    {{ $purchase->warehouse->phone }}.
                                @else
                                    {{ json_decode($generalSettings->business, true)['phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>P.Invoice ID : </strong> {{ $purchase->invoice_id }}</li>
                            <li><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                            <li><strong>Purchase Status : </strong>
                                <span class="purchase_status">
                                    @if ($purchase->purchase_status == 1)
                                        Purchased
                                    @elseif($purchase->purchase_status == 2){
                                        Pending
                                    @else
                                        Purchased By Order
                                    @endif
                                </span>
                            </li>
                            <li><strong>Payment Status : </strong>
                               @php
                                   $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                               @endphp
                               @if ($purchase->due <= 0)
                                   Paid
                               @elseif($purchase->due > 0 && $purchase->due < $payable)
                                   Partial
                               @elseif($payable == $purchase->due)
                                   Due
                               @endif
                            </li>
                            <li><strong>@lang('menu.created_by') : </strong>
                                {{ $purchase->admin ? $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name : '' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <table class="table modal-table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">@lang('menu.description')</th>
                            <th scope="col">@lang('menu.quantity')</th>
                            <th scope="col">Unit Cost({{ json_decode($generalSettings->business, true)['currency'] }}) </th>
                            <th scope="col">Unit Discount({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">Tax(%)</th>
                            <th scope="col">Net Unit Cost({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                            <th scope="col">Lot Number</th>
                            <th scope="col">SubTotal({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($purchase->purchase_products as $product)
                            <tr>
                                @php
                                    $variant = $product->variant ? ' ('.$product->variant->variant_name.')' : '';
                                @endphp

                                <td>
                                    {{ Str::limit($product->product->name, 25).' '.$variant }}
                                    <small>{!! $product->description ? '<br/>'.$product->description : '' !!}</small>
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    {{ App\Utils\Converter::format_in_bdt($product->unit_cost) }}
                                </td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->unit_discount) }} </td>
                                <td>{{ $product->unit_tax.'('.$product->unit_tax_percent.'%)' }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->net_unit_cost) }}</td>
                                <td>{{ $product->lot_no ? $product->lot_no : '' }}</td>
                                <td>{{ App\Utils\Converter::format_in_bdt($product->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-end">Net Total Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-end">Purchase Discount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }} {{$purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-end">Purchase Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="7" class="text-end">Shipment Charge : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="7" class="text-end">Purchase Total : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="7" class="text-end">Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                            <td class="text-end">
                                {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="7" class="text-end">@lang('menu.due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
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
                    <h6>CHECKED BY : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>APPROVED BY : </h6>
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
