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
<div class="purchase_print_template">
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
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>
                        @if ($purchase?->branch)
                            @if ($purchase?->branch?->parent_branch_id)

                                {{ $purchase?->branch?->parentBranch?->name }}
                            @else

                                {{ $purchase?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__shop_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($purchase?->branch)

                        {{ $purchase->branch->city . ', ' . $purchase->branch->state. ', ' . $purchase->branch->zip_code. ', ' . $purchase->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($purchase?->branch)

                        <strong>@lang('menu.email') : </strong> <b>{{ $purchase?->branch?->email }}</b>,
                        <strong>@lang('menu.phone') : </strong> <b>{{ $purchase?->branch?->phone }}</b>
                    @else

                        <strong>@lang('menu.email') : </strong> <b>{{ $generalSettings['business__email'] }}</b>,
                        <strong>@lang('menu.phone') : </strong> <b>{{ $generalSettings['business__phone'] }}</b>
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 style="text-transform: uppercase;"><strong>{{ __("Purchase Invoice") }}</strong></h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Supplier") }} : </strong><b>{{ $purchase->supplier->name }}</b></li>
                    <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong><b>{{ $purchase->supplier->address }}</b></li>
                    <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong><b>{{ $purchase->supplier->phone }}</b></li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>
                        <b>{{ date($generalSettings['business__date_format'], strtotime($purchase->date)) }}</b>
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Invoice ID") }} : </strong><b>{{ $purchase->invoice_id }}</b></li>

                    <li style="font-size:11px!important;"><strong>{{ __("Payment Status") }} : </strong>
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

                    <li style="font-size:11px!important;"><strong>{{ __("Created By") }} : </strong>
                        <b>{{ $purchase?->admin?->prefix.' '.$purchase?->admin?->name.' '.$purchase?->admin?->last_name }}</b>
                    </li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table modal-table table-sm table-bordered">
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
                                    <small class="d-block text-muted"><strong>{{ __("Batch No") }} :</strong>  {{ $purchaseProduct->batch_number }}, <strong>{{ __("Expire Date") }} :</strong> {{ $purchaseProduct->expire_date ? date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) : '' }}</small>
                                @endif
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ $purchaseProduct->quantity }}</td>
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
                <table class="table modal-table table-sm">
                    <thead>
                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->net_total_amount) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Purchase Discount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($purchase->order_discount_type == 1)

                                    <b>({{ __("Fixed") }})={{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}</b>
                                @else

                                    <b>({{ App\Utils\Converter::format_in_bdt($purchase->order_discount) }}%=)
                                    {{ App\Utils\Converter::format_in_bdt($purchase->order_discount_amount) }}</b>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Purchase Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ '('.$purchase->purchase_tax_percent.'%)='. $purchase->purchase_tax_amount }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Shipment Charge") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->shipment_charge) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Purchase Total') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($payingAmount) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Due (On Invoice)") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt($purchase->due) }}</b>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Current Balance") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                <b>{{ App\Utils\Converter::format_in_bdt(0) }}</b>
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
                <p><b>{{ $purchase->invoice_id }}</b></p>
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
