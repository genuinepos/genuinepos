@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">
                     @lang('menu.purchase_details') (@lang('menu.reference_id') : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h5>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : - </strong></li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.name') : </strong> <span class="supplier_name">{{ $purchase->supplier->name }}</span></li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> <span class="supplier_address">{{ $purchase->supplier->address }}</span></li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.tax_number') : </strong> <span class="supplier_tax_number">{{ $purchase->supplier->tax_number }}</span></li>
                             <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> <span class="supplier_phone">{{ $purchase->supplier->phone }}</span></li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong> {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                             <li style="font-size:11px!important;"><strong>{{ __('P.Invoice ID') }} : </strong> {{ $purchase->invoice_id }}</li>

                             <li style="font-size:11px!important;">
                                <strong>@lang('menu.purchases_status') : </strong>
                                @if ($purchase->purchase_status == 1)
                                    <span class="badge bg-success">@lang('menu.purchased') : </span>
                                @elseif($purchase->purchase_status == 2){
                                    <span class="badge bg-warning text-white">@lang('menu.pending') : </span>
                                }
                                @else
                                    <span class="badge bg-primary">@lang('menu.purchased_by_order') : </span>
                                @endif
                             </li>

                             <li style="font-size:11px!important;"><strong>@lang('menu.payment_status') : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">@lang('menu.paid') : </span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">@lang('menu.partial') : </span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">@lang('menu.due') : </span>
                                @endif
                             </li>
                             <li style="font-size:11px!important;">
                                 <strong>@lang('menu.created_by') : </strong>
                                {{ $purchase->admin ? $purchase->admin->prefix.' '.$purchase->admin->name.' '.$purchase->admin->last_name : 'N/A' }}
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.purchase_from') : </strong></li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.business_location') : </strong>
                               @if ($purchase->branch_id)
                                   {{ $purchase->branch->name . '/' . $purchase->branch->branch_code }}(BL)
                               @else
                                   {{ $generalSettings['business__shop_name'] }} (HO)
                               @endif
                           </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong>
                               @if ($purchase->branch)
                                   {{ $purchase->branch->phone }}, <br>
                               @elseif($purchase->warehouse_id)
                                   {{ $purchase->warehouse->phone }}
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
                             <table id="" class="table modal-table table-sm table-striped">
                                 <thead>
                                     <tr class="bg-secondary">
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.product')</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.quantity')</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.before_discount'))</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.before_tax'))</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.sub_total') (@lang('menu.before_tax'))</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.unit_cost')(@lang('menu.after_tax'))</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.unit_selling_price')</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">@lang('menu.lot_number')</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchase_products as $purchaseProduct)
                                        <tr>
                                            @php
                                                $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ $purchaseProduct->product->name.' '.$variant }}
                                                @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                                    <small class="d-block text-muted"><strong>@lang('menu.batch_no') :</strong>  {{ $purchaseProduct->batch_number }}, <strong>@lang('menu.expire_date') :</strong> {{ date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->quantity }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ $generalSettings['business__currency'].''.$purchaseProduct->unit_cost }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }} </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_with_discount) }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->subtotal) }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->unit_tax.'('.$purchaseProduct->unit_tax_percent.'%)' }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }} </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->product->product_price) }}</td>

                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
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
                                            <th class="text-start fw-bold" style="font-size:11px!important;">@lang('menu.date')</th>
                                            <th class="text-start fw-bold" style="font-size:11px!important;">@lang('menu.voucher_no')</th>
                                            <th class="text-start fw-bold" style="font-size:11px!important;">@lang('menu.method')</th>
                                            <th class="text-start fw-bold" style="font-size:11px!important;">@lang('menu.type')</th>
                                            <th class="text-start fw-bold" style="font-size:11px!important;">@lang('menu.account')</th>
                                            <th class="text-start fw-bold" style="font-size:11px!important;">
                                                @lang('menu.amount')({{ $generalSettings['business__currency'] }})
                                            </th>
                                            <th class="text-start fw-bold action_hideable" style="font-size:11px!important;">@lang('menu.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="p_details_payment_list">
                                       @if (count($purchase->purchase_payments) > 0)
                                           @foreach ($purchase->purchase_payments as $payment)
                                               <tr data-info="{{ $payment }}">
                                                   <td class="text-start fw-bold" style="font-size:11px!important;">{{ date($generalSettings['business__date_format'], strtotime($payment->date)) }}</td>
                                                   <td class="text-start fw-bold" style="font-size:11px!important;">{{ $payment->invoice_id }}</td>
                                                   <td class="text-start fw-bold" style="font-size:11px!important;">{{ $payment->pay_mode }}</td>
                                                   <td class="text-start fw-bold" style="font-size:11px!important;">
                                                        @if ($payment->is_advanced == 1)
                                                            @lang('menu.po_advance_payment')
                                                        @else
                                                            {{ $payment->payment_type == 1 ? 'Purchase Payment' : 'Received Return Amt.' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-start fw-bold" style="font-size:11px!important;">
                                                        {{ $payment->account ? $payment->account->name.' (A/C'.$payment->account->account_number.')' : 'N/A' }}
                                                    </td>
                                                    <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payment->paid_amount) }}</td>
                                                    <td class="action_hideable text-start fw-bold" style="font-size:11px!important;">
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
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">@lang('menu.net_total_amount') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">@lang('menu.purchase_discount') : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">@lang('menu.purchase_tax') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">@lang('menu.shipment_charge') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.grand_total') : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                           {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.paid') : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">@lang('menu.due') : {{ $generalSettings['business__currency'] }}</th>
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
                             <p style="font-size:11px!important;">@lang('menu.shipping_details') </p>
                             <p class="shipping_details" style="font-size:11px!important;">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;">@lang('menu.purchase_not') </p>
                             <p class="purchase_note" style="font-size:11px!important;">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('purchases.edit', [$purchase->id, 'purchased']) }}" class="btn btn-sm btn-secondary">@lang('menu.edit')</a>
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
 @php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
 <!-- Purchase print templete-->
<div class="purchase_print_template d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
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

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;">
                    <strong>
                        @if ($purchase->branch)

                            {!! $purchase->branch->name.' '.$purchase->branch->branch_code.' (BL)' !!}
                        @else

                            {{ $generalSettings['business__shop_name'] }} (HO)
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($purchase?->branch)

                        {{  $sale->branch->city . ', ' . $sale->branch->state. ', ' . $sale->branch->zip_code. ', ' . $sale->branch->country }},
                        {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                        {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                        {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($purchase?->branch)

                        <strong>@lang('menu.email') : </strong> {{ $purchase?->branch?->email }},
                        <strong>@lang('menu.phone') : </strong> {{ $purchase?->branch?->phone }}
                    @else

                        <strong>@lang('menu.email') : </strong> {{ $generalSettings['business__email'] }},
                        <strong>@lang('menu.phone') : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 style="text-transform: uppercase;"><strong>@lang('menu.purchase_invoice')</strong></h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.supplier') : </strong>{{ $purchase->supplier->name }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong>{{ $purchase->supplier->address }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong>{{ $purchase->supplier->phone }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong>
                        {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) }}
                    </li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.p_invoice_id') : </strong>{{ $purchase->invoice_id }}</li>

                    <li style="font-size:11px!important;"><strong>@lang('menu.payment_status') : </strong>
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

                    <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>
                        {{ $purchase?->admin?->prefix.' '.$purchase?->admin?->name.' '.$purchase?->admin?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.description')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.quantity')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.unit_cost')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.tax')(%)</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Net Unit Cost') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.lot_number')</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">@lang('menu.subtotal')</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($purchase->purchase_products as $purchaseProduct)
                        <tr>
                            @php
                                $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->variant_name : '';
                            @endphp

                            <td class="text-start" style="font-size:11px!important;">
                                <p>{{ Str::limit($purchaseProduct->product->name, 25).' '. $variant }}</p>
                                <small class="d-block text-muted">{!! $purchaseProduct->description ? $purchaseProduct->description : '' !!}</small>

                                @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                    <small class="d-block text-muted"><strong>@lang('menu.batch_no') :</strong>  {{ $purchaseProduct->batch_number }}, <strong>@lang('menu.expire_date') :</strong> {{ date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) }}</small>
                                @endif
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $purchaseProduct->quantity }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost) }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }} </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $purchaseProduct->unit_tax.'('.$purchaseProduct->unit_tax_percent.'%)' }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6 offset-6">
                <table class="table modal-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.purchase_discount') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($purchase->order_discount_type == 1)

                                    (@lang('menu.fixed')) {{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}
                                @else

                                    ({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%)
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.purchase_tax') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ '('.$purchase->purchase_tax_percent.'%)'. $purchase->purchase_tax_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Purchase Total') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.paid') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">@lang('menu.due') : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12 text-center">
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($purchase->invoice_id, $generator::TYPE_CODE_128)) }}">
                <p>{{ $purchase->invoice_id }}</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.PRINT_SD_COMPANY_NAME'))
                        <small class="d-block">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->

