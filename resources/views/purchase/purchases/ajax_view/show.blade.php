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
                    {{ __("Purchase Details") }} ({{ __("Invoice ID") }} : <strong>{{ $purchase->invoice_id }}</strong>)
                 </h6>
                 <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-4">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Supplier") }} : - </strong></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Name") }} : </strong> <span class="supplier_name">{{ $purchase->supplier->name }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong> <span class="supplier_address">{{ $purchase->supplier->address }}</span></li>
                             <li style="font-size:11px!important;"><strong>{{ __("Phone") }}: </strong> <span class="supplier_phone">{{ $purchase->supplier->phone }}</span></li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                         <ul class="list-unstyled">
                             <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) . ' ' . date($timeFormat, strtotime($purchase->time)) }}</li>
                             <li style="font-size:11px!important;"><strong>{{ __('P.Invoice ID') }} : </strong> {{ $purchase->invoice_id }}</li>
                             <li style="font-size:11px!important;"><strong>{{ __("Payment Status") }} : </strong>
                                @php
                                    $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                                @endphp
                                @if ($purchase->due <= 0)
                                     <span class="badge bg-success">{{ __("Paid") }} : </span>
                                @elseif($purchase->due > 0 && $purchase->due < $payable)
                                    <span class="badge bg-primary text-white">{{ __("Partial") }} : </span>
                                @elseif($payable == $purchase->due)
                                    <span class="badge bg-danger text-white">{{ __("Due") }} : </span>
                                @endif
                             </li>
                             <li style="font-size:11px!important;">
                                 <strong>{{ __("Created By") }} : </strong>
                                {{ $purchase?->admin?->prefix .' '. $purchase?->admin?->name .' '. $purchase?->admin?->last_name }}
                             </li>
                         </ul>
                     </div>

                     <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Shop/Business") }} : </strong>
                                @if ($purchase->branch_id)

                                    @if($purchase?->branch?->parentBranch)

                                        {{ $purchase?->branch?->parentBranch?->name . '(' . $purchase?->branch?->area_name . ')'.'-('.$purchase?->branch?->branch_code.')' }}
                                    @else

                                        {{ $purchase?->branch?->name . '(' . $purchase?->branch?->area_name . ')'.'-('.$purchase?->branch?->branch_code.')' }}
                                    @endif
                                @else

                                    {{ $generalSettings['business__shop_name'] }}
                                @endif
                           </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>
                                @if ($purchase->branch)

                                    {{ $purchase->branch->phone }}
                                @else

                                    {{ $generalSettings['business__phone'] }}
                                @endif
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Stored Location") }} : </strong>
                                @if ($purchase?->warehouse)
                                    {{ $purchase?->warehouse?->warehouse_name.'/'.$purchase?->warehouse?->warehouse_code.'-(WH)' }}
                                @else
                                    @if ($purchase->branch_id)

                                        @if($purchase?->branch?->parentBranch)

                                            {{ $purchase?->branch?->parentBranch?->name . '(' . $purchase?->branch?->area_name . ')'.'-('.$purchase?->branch?->branch_code.')' }}
                                        @else

                                            {{ $purchase?->branch?->name . '(' . $purchase?->branch?->area_name . ')'.'-('.$purchase?->branch?->branch_code.')' }}
                                        @endif
                                    @else

                                        {{ $generalSettings['business__shop_name'] }}
                                    @endif
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
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Product") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Quantity") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Unit Cost (Before Discount)") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Unit Discount") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Unit Cost (Before Tax)") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Subtotal (Before Tax)") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Unit Cost (After Tax)") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Line-Total") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Lot No") }}</th>
                                         <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __("Selling Price") }}</th>
                                     </tr>
                                 </thead>
                                 <tbody class="purchase_product_list">
                                     @foreach ($purchase->purchaseProducts as $purchaseProduct)
                                        <tr>
                                            @php
                                                $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->variant_name : '';
                                            @endphp

                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ $purchaseProduct->product->name.' '.$variant }}
                                                @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                                    <small class="d-block text-muted" style="font-size: 9px;">{{ __("Batch No") }} : {{ $purchaseProduct->batch_number }}, {{ __("Expire Date") }} :{{ $purchaseProduct->expire_date ? date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                                @endif
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->quantity.'/'.$purchaseProduct?->unit?->code_name }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_exc_tax) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_with_discount) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->subtotal) }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                {{ '(' . $purchaseProduct->unit_tax_percent.'%)=' . $purchaseProduct->unit_tax_amount }}
                                            </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->net_unit_cost) }} </td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ $purchaseProduct->lot_no ? $purchaseProduct->lot_no : '' }}</td>
                                            <td class="text-start fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->selling_price) }}</td>
                                        </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __("Payments Against Purchase") }}</p>
                        @include('purchase.purchases.ajax_view.partials.purchase_details_payment_list')
                    </div>

                    <div class="col-md-5">
                         <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Purchase Discount") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ $purchase->order_discount }} {{ $purchase->order_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Purchase Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ $purchase->purchase_tax_amount.' ('.$purchase->purchase_tax_percent.'%)' }}
                                   </td>
                                </tr>
                                <tr>
                                    <th class="text-end">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Total Purchased Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                           {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Return') }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->purchase_return_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Due (On Invoice)") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
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
                             <p style="font-size:11px!important;"><strong>@lang('menu.shipping_details')</strong></p>
                             <p class="shipping_details" style="font-size:11px!important;">{{ $purchase->shipment_details }}</p>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="details_area">
                             <p style="font-size:11px!important;"><strong>@lang('menu.purchase_not')</strong></p>
                             <p class="purchase_note" style="font-size:11px!important;">{{ $purchase->purchase_note }}</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            <a href="{{ route('purchases.edit', [$purchase->id]) }}" class="btn btn-sm btn-secondary">@lang('menu.edit')</a>
                            <button type="submit" class="footer_btn btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __("Print") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:0px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>

<!-- Purchase print templete-->
<div class="print_modal_details d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($purchase->branch)

                    @if ($purchase?->branch?->parent_branch_id)

                        @if ($purchase->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($purchase->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch?->name }}</span>
                        @endif
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
                <p style="text-transform: uppercase;" class="p-0 m-0 fw-bold">
                    @if ($purchase?->branch)
                        @if ($purchase?->branch?->parent_branch_id)

                            {{ $purchase?->branch?->parentBranch?->name }}
                        @else

                            {{ $purchase?->branch?->name }}
                        @endif
                    @else

                        {{ $generalSettings['business__shop_name'] }}
                    @endif
                </p>

                <p style="font-size:12px!important;">
                    @if ($purchase?->branch)

                        {{ $purchase->branch->city . ', ' . $purchase->branch->state. ', ' . $purchase->branch->zip_code. ', ' . $purchase->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p style="font-size:12px!important;">
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
                <h5 style="text-transform: uppercase;" class="fw-bold">{{ __("Purchase Invoice") }}</h5>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Supplier") }} : </strong>{{ $purchase->supplier->name }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong>{{ $purchase->supplier->address }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>{{ $purchase->supplier->phone }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        {{ date($generalSettings['business__date_format'], strtotime($purchase->date)) }}
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Invoice ID") }} : </strong>{{ $purchase->invoice_id }}</li>

                    <li style="font-size:11px!important;"><strong>{{ __("Payment Status") }} : </strong>
                        @php
                            $payable = $purchase->total_purchase_amount - $purchase->total_return_amount;
                        @endphp
                        @if ($purchase->due <= 0)
                            {{ __("Paid") }}
                        @elseif($purchase->due > 0 && $purchase->due < $payable)
                            {{ __("Partial") }}
                        @elseif($payable == $purchase->due)
                            {{ __("Due") }}
                        @endif
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        {{ $purchase?->admin?->prefix.' '.$purchase?->admin?->name.' '.$purchase?->admin?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table print-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Description") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Quantity") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost (Exc. Tax)") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Discount") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Tax") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Net Unit Cost') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Lot Number") }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                    </tr>
                </thead>
                <tbody class="purchase_print_product_list">
                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                        <tr>
                            @php
                                $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->variant_name : '';
                            @endphp

                            <td class="text-start" style="font-size:11px!important;">
                                <p>{{ Str::limit($purchaseProduct->product->name, 25).' '. $variant }}</p>
                                <small class="d-block text-muted">{!! $purchaseProduct->description ? $purchaseProduct->description : '' !!}</small>
                                @if ($purchaseProduct?->product?->has_batch_no_expire_date)
                                    <small class="d-block text-muted" style="font-size: 9px;">{{ __("Batch No") }} : {{ $purchaseProduct->batch_number }}, {{ __("Expire Date") }} : {{ $purchaseProduct->expire_date ? date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                @endif
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $purchaseProduct->quantity.'/'.$purchaseProduct?->unit?->code_name }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_cost_exc_tax) }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($purchaseProduct->unit_discount) }} </td>
                            <td class="text-start" style="font-size:11px!important;">{{ '('.$purchaseProduct->unit_tax_percent.'%)='.$purchaseProduct->unit_tax_amount }}</td>
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
                <table class="table print-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Purchase Discount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($purchase->order_discount_type == 1)

                                    ({{ __("Fixed") }})={{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}
                                @else

                                    ({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%=)
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Purchase Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ '('.$purchase->purchase_tax_percent.'%)='. $purchase->purchase_tax_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Purchased Amount') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Return') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->purchase_return_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Due (On Invoice)") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($purchase->due) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Current Balance") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt(0) }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <br/><br/>
        <div class="row">
            <div class="col-4 text-start">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Prepared By") }}
                </p>
            </div>

            <div class="col-4 text-center">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Checked By") }}
                </p>
            </div>

            <div class="col-4 text-end">
                <p class="text-uppercase" style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">
                    {{ __("Authorized By") }}
                </p>
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
                    <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">{{ __("Powered By") }} <strong>SpeedDigit Software Solution.</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Purchase print templete end-->

