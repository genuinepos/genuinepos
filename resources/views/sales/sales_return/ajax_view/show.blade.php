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
                    {{ __('Sales Return Details') }} | ({{ __('Voucher No') }}  : <strong>{{ $return->voucher_no }}</strong>)
                </h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Customer") }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __("Name") }} : </strong> <span>{{ $return->customer->name }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __("Address") }} : </strong> <span>{{ $return->customer->address }}</span></li>
                            <li style="font-size:11px!important;"><strong>{{ __("Phone") }}: </strong> <span>{{ $return->customer->phone }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong> {{ date($generalSettings['business__date_format'], strtotime($return->date)) . ' ' . date($timeFormat, strtotime($return->time)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong> {{ $return->voucher_no }}</li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __("Created By") }} : </strong>
                               {{ $return?->createdBy?->prefix .' '. $return?->createdBy?->name .' '. $return?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                       <ul class="list-unstyled">
                           <li style="font-size:11px!important;"><strong>{{ __("Shop/Business") }} : </strong>
                               @if ($return->branch_id)

                                   @if($return?->branch?->parentBranch)

                                       {{ $return?->branch?->parentBranch?->name . '(' . $return?->branch?->area_name . ')'.'-('.$return?->branch?->branch_code.')' }}
                                   @else

                                       {{ $return?->branch?->name . '(' . $return?->branch?->area_name . ')'.'-('.$return?->branch?->branch_code.')' }}
                                   @endif
                               @else

                                   {{ $generalSettings['business__shop_name'] }}
                               @endif
                          </li>

                           <li style="font-size:11px!important;"><strong>{{ __("Phone") }} : </strong>
                               @if ($return->branch)

                                   {{ $return->branch->phone }}
                               @else

                                   {{ $generalSettings['business__phone'] }}
                               @endif
                           </li>

                           <li style="font-size:11px!important;"><strong>{{ __("Stored Location") }} : </strong>
                               @if ($return?->warehouse)

                                   {{ $return?->warehouse?->warehouse_name . '/' . $return?->warehouse?->warehouse_code.'-(WH)' }}
                               @else
                                   @if ($return->branch_id)

                                       @if($return?->branch?->parentBranch)

                                           {{ $return?->branch?->parentBranch?->name . '(' . $return?->branch?->area_name . ')'.'-('.$return?->branch?->branch_code.')' }}
                                       @else

                                           {{ $return?->branch?->name . '(' . $return?->branch?->area_name . ')'.'-('.$return?->branch?->branch_code.')' }}
                                       @endif
                                   @else

                                       {{ $generalSettings['business__shop_name'] }}
                                   @endif
                               @endif
                           </li>
                       </ul>
                   </div>
                </div>

                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("S/L") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Product") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Return Qty") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost(Exc. Tax)") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Discount") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Cost(Inc. Tax)") }}</th>
                                    <th class="text-white fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                                </tr>
                            </thead>
                            <tbody class="purchase_return_product_list">
                                @foreach ($return->saleReturnProducts as $saleReturnProduct)
                                    <tr>
                                        @php
                                            $variant = $saleReturnProduct->variant ? ' - ' . $saleReturnProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ $saleReturnProduct->product->name . $variant }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->return_qty) }}/{{ $saleReturnProduct?->unit?->code_name }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_inc_exc_tax) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_discount_amount) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ '('.$saleReturnProduct->unit_tax_percent.')='.App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_tax_amount) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_inc_tax) }}
                                        </td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->return_subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __("Payments Against Sales Return Voucher") }}</p>
                        @include('sales.sales_return.ajax_view.partials.sales_return_details_payment_list')
                    </div>

                    <div class="col-md-5">
                         <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __("Net Total Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Return Discount") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ $return->return_discount }} {{ $return->return_discount_type == 1 ? '(Fixed)' : '%' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Return Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ $return->return_tax_amount.' ('.$return->return_tax_percent.'%)' }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Total Returned Amount") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->paid) }}
                                   </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __("Due (On Return Voucher)") }} : {{ $generalSettings['business__currency'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($return->due) }}
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
            </div>

            <div class="modal-footer">
                <a href="{{ route('sales.returns.edit', $return->id) }}" class="btn btn-sm btn-secondary">{{ __("Edit") }}</a>
                <button type="submit" class="btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __("Print") }}</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
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

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:0px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
<!-- purchase print templete-->
<div class="print_modal_details d-hide">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($return->branch)

                    @if ($return?->branch?->parent_branch_id)

                        @if ($return->branch?->parentBranch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->parentBranch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->parentBranch?->name }}</span>
                        @endif
                    @else

                        @if ($return->branch?->logo != 'default.png')

                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $return->branch?->logo) }}">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $return->branch?->name }}</span>
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
                        @if ($return?->branch)
                            @if ($return?->branch?->parent_branch_id)

                                {{ $return?->branch?->parentBranch?->name }}
                            @else

                                {{ $return?->branch?->name }}
                            @endif
                        @else

                            {{ $generalSettings['business__shop_name'] }}
                        @endif
                    </strong>
                </p>

                <p>
                    @if ($return?->branch)

                        {{ $return->branch->city . ', ' . $return->branch->state. ', ' . $return->branch->zip_code. ', ' . $return->branch->country }}
                    @else

                        {{ $generalSettings['business__address'] }}
                    @endif
                </p>

                <p>
                    @if ($return?->branch)

                        <strong>{{ __("Email") }} : </strong> {{ $return?->branch?->email }},
                        <strong>{{ __("Phone") }} : </strong> {{ $return?->branch?->phone }}
                    @else

                        <strong>{{ __("Email") }} : </strong> {{ $generalSettings['business__email'] }},
                        <strong>{{ __("Phone") }} : </strong> {{ $generalSettings['business__phone'] }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4 style="text-transform: uppercase;"><strong>{{ __("Sales Return Voucher") }}</strong></h4>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __('Voucher No') }} : </strong>{{ $return->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Date") }} : </strong>{{ $return->date }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Customer") }} : </strong> {{ $return?->customer?->name  }}</li>
                </ul>
            </div>

            <div class="col-6">
                <ul class="list-unstyled float-right">
                    <li style="font-size:11px!important;"><strong>{{ __("Sales Invoice Details") }} : </strong> </li>
                    <li style="font-size:11px!important;"><strong>{{ __("Invoice ID") }} : </strong> {{ $return->sale ? $return->sale->invoice_id : 'N/A' }}</li>
                    <li style="font-size:11px!important;"><strong>{{ __("Sale Date") }} : </strong>{{ $return->sale ? $return->sale->date : 'N/A' }}</li>
                </ul>
            </div>
        </div>

        <div class="purchase_product_table pt-1 pb-1">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("S/L") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Product") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Return Qty") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Price(Exc. Tax)") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Discount") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Vat/Tax") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Unit Price(Inc. Tax)") }}</th>
                            <th class="fw-bold text-start" style="font-size:11px!important;">{{ __("Subtotal") }}</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="purchase_return_print_product_list">
                    @foreach ($return->saleReturnProducts as $saleReturnProduct)
                        @if ($saleReturnProduct->return_qty > 0)
                            <tr>
                                @php
                                    $variant = $saleReturnProduct->variant ? ' - ' . $saleReturnProduct->variant->variant_name : '';
                                @endphp
                                <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $saleReturnProduct->product->name . $variant }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->return_qty) }}/{{ $saleReturnProduct?->unit?->code_name }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_exc_tax) }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_discount_amount) }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ '('.$saleReturnProduct->unit_tax_percent.')='.App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_tax_amount) }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ App\Utils\Converter::format_in_bdt($saleReturnProduct->unit_price_inc_tax) }}
                                </td>

                                <td class="text-start" style="font-size:11px!important;">
                                    {{ $saleReturnProduct->return_subtotal }}
                                </td>
                            </tr>
                        @endif
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
                                {{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Return Discount") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                @if ($return->return_discount_type == 1)

                                    ({{ __("Fixed") }})={{ App\Utils\Converter::format_in_bdt($return->return_discount) }}
                                @else

                                    ({{ App\Utils\Converter::format_in_bdt($return->return_discount) }}%=)
                                    {{ App\Utils\Converter::format_in_bdt($return->return_discount_amount) }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Return Tax") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ '('.$return->return_tax_percent.'%)='. $return->return_tax_amount }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __('Total Returned Amount') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Paid") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($return->paid) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end fw-bold" style="font-size:11px!important;">{{ __("Due (On Return Voucher)") }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($return->due) }}
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
                <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($return->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p><b>{{ $return->voucher_no }}</b></p>
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
